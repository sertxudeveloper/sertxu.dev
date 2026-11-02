#!/usr/bin/env bash
#
# Smoke test for the application image.
#
# These are the checks that only the container image can answer: the PHP
# version and extensions it ships with, the Composer autoloader, the compiled
# frontend assets, whether the application boots, and whether the container
# comes up and serves HTTP.
#
# Usage:
#   IMAGE=registry/repo@sha256:... bash docker/smoke-test.sh
#
# Optional environment variables:
#   SMOKE_MIN_PHP              minimum acceptable PHP version, e.g. 8.5.12
#   SMOKE_DB_HOST              enables the container/database checks when set.
#                              Resolved inside the container, so a database on
#                              the host must be addressed as host.docker.internal
#                              rather than 127.0.0.1 (which is the container).
#   SMOKE_DB_PORT              defaults to 5432
#   SMOKE_DB_DATABASE          defaults to postgres
#   SMOKE_DB_USERNAME          defaults to postgres
#   SMOKE_DB_PASSWORD          defaults to postgres
#
set -euo pipefail

IMAGE="${IMAGE:?Set IMAGE to the image reference to test}"
CONTAINER_NAME="sertxudev-smoke-$$"
HTTP_PORT="${SMOKE_HTTP_PORT:-18080}"

REQUIRED_EXTENSIONS=(
    bcmath
    ctype
    curl
    dom
    exif
    fileinfo
    gd
    intl
    mbstring
    openssl
    pcntl
    pdo_pgsql
    redis
    tokenizer
    xml
    zip
    opcache
)

cleanup() {
    docker rm -f "$CONTAINER_NAME" >/dev/null 2>&1 || true
}
trap cleanup EXIT

info() {
    printf '\n==> %s\n' "$1"
}

fail() {
    printf '\n!! FAILED: %s\n' "$1" >&2
    exit 1
}

info "Image $IMAGE"
docker pull "$IMAGE"

info "PHP version"
php_version="$(docker run --rm --entrypoint php "$IMAGE" -v | head -n 1)"
echo "$php_version"
echo "$php_version" | grep -qE 'PHP 8\.[0-9]+\.' || fail "expected a PHP 8.x runtime, got: $php_version"

if [ -n "${SMOKE_MIN_PHP:-}" ]; then
    php_version_number="$(echo "$php_version" | sed -nE 's/^PHP ([0-9]+\.[0-9]+\.[0-9]+).*/\1/p')"
    [ -n "$php_version_number" ] || fail "could not parse the PHP version from: $php_version"
    [ "$(printf '%s\n%s\n' "$SMOKE_MIN_PHP" "$php_version_number" | sort -V | head -n 1)" = "$SMOKE_MIN_PHP" ] \
        || fail "PHP $php_version_number is older than the required $SMOKE_MIN_PHP"
    echo "PHP $php_version_number satisfies the required minimum $SMOKE_MIN_PHP"
fi

info "Required PHP extensions"
extensions="$(docker run --rm --entrypoint php "$IMAGE" -m)"
for extension in "${REQUIRED_EXTENSIONS[@]}"; do
    # opcache is reported as "Zend OPcache" in the [Zend Modules] section.
    echo "$extensions" | grep -qiE "^(zend[[:space:]]+)?${extension}$" || fail "missing PHP extension: ${extension}"
done
echo "all ${#REQUIRED_EXTENSIONS[@]} required extensions are present"

info "Application files"
docker run --rm --entrypoint php "$IMAGE" -r 'exit(is_file("/var/www/html/vendor/autoload.php") ? 0 : 1);' \
    || fail "missing /var/www/html/vendor/autoload.php"
docker run --rm --entrypoint php "$IMAGE" -r 'exit(is_file("/var/www/html/public/build/manifest.json") ? 0 : 1);' \
    || fail "missing /var/www/html/public/build/manifest.json"
echo "vendor autoloader and compiled frontend assets are present"

info "Application boots"
docker run --rm --entrypoint php "$IMAGE" artisan --version || fail "artisan could not boot"

if [ -z "${SMOKE_DB_HOST:-}" ]; then
    printf '\nSmoke test passed (container/database checks skipped, SMOKE_DB_HOST not set).\n'
    exit 0
fi

APP_KEY="$(docker run --rm --entrypoint php "$IMAGE" -r 'echo "base64:" . base64_encode(random_bytes(32));')"

db_env=(
    -e APP_ENV=production
    -e APP_DEBUG=false
    -e APP_KEY="$APP_KEY"
    -e DB_CONNECTION=pgsql
    -e DB_HOST="$SMOKE_DB_HOST"
    -e DB_PORT="${SMOKE_DB_PORT:-5432}"
    -e DB_DATABASE="${SMOKE_DB_DATABASE:-postgres}"
    -e DB_USERNAME="${SMOKE_DB_USERNAME:-postgres}"
    -e DB_PASSWORD="${SMOKE_DB_PASSWORD:-postgres}"
)

# Bootstrap the empty database before the container starts. The entrypoint
# migrates with --isolated, which needs the cache_locks table.
# Bypass the entrypoint so this first migration runs without isolation.
info "Preparing the database"
docker run --rm --add-host=host.docker.internal:host-gateway \
    --entrypoint php "${db_env[@]}" "$IMAGE" \
    artisan migrate --force \
    || fail "the migrations could not be run"

info "Starting the container"
# The container has its own network namespace, so 127.0.0.1 is the container
# itself. Map host.docker.internal to the Docker host gateway so a database
# published on the host is reachable (requires Docker Engine 20.10+).
docker run -d --name "$CONTAINER_NAME" \
    --add-host=host.docker.internal:host-gateway \
    -p "${HTTP_PORT}:8080" \
    "${db_env[@]}" \
    "$IMAGE" >/dev/null

info "Waiting for the container to become healthy"
health=""
for _ in $(seq 1 60); do
    health="$(docker inspect -f '{{if .State.Health}}{{.State.Health.Status}}{{else}}{{.State.Status}}{{end}}' "$CONTAINER_NAME" 2>/dev/null || echo unknown)"
    case "$health" in
        healthy) break ;;
        unhealthy | exited | dead)
            docker logs "$CONTAINER_NAME" || true
            fail "the container reached the '$health' state"
            ;;
    esac
    sleep 2
done
[ "$health" = "healthy" ] || { docker logs "$CONTAINER_NAME" || true; fail "the container never became healthy"; }
echo "container is healthy"

info "Health endpoint"
status_code="$(curl -fsS -o /dev/null -w '%{http_code}' "http://127.0.0.1:${HTTP_PORT}/up" || echo 000)"
[ "$status_code" = "200" ] || fail "GET /up returned HTTP ${status_code}"
echo "GET /up returned HTTP 200"

info "Database connectivity and migrations"
docker exec "$CONTAINER_NAME" php artisan migrate:status >/dev/null \
    || fail "the application could not reach the database"
docker exec "$CONTAINER_NAME" php artisan migrate:status | tail -n 5

printf '\nSmoke test passed.\n'
