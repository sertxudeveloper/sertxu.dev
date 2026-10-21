#!/bin/sh

# Global configurations
: "${APP_BASE_DIR:=/var/www/html}"

# Only the web deployment purges the cache, the worker and scheduler pods skip it.
: "${CLOUDFLARE_PURGE_ENABLED:=true}"

if [ "$CLOUDFLARE_PURGE_ENABLED" != "true" ]; then
    exit 0
fi

if [ -z "${CFCACHE_API_TOKEN:-}" ] || [ -z "${CFCACHE_ZONE_ID:-}" ]; then
    echo "⚠️ Cloudflare credentials are missing, skipping the cache purge."
    exit 0
fi

php "$APP_BASE_DIR/artisan" cloudflare:purge --all --force || \
    echo "⚠️ Cloudflare cache purge failed, continuing."

exit 0
