# syntax=docker/dockerfile:1

# Chromium for Browsershot/Puppeteer, downloaded in its own stage so the ~170 MB
# fetch is only re-run when Puppeteer itself changes and not on every lockfile
# update. CI resolves the version from package-lock.json and passes it in as a
# build argument, which keeps this stage cached.
FROM node:24-bookworm-slim AS browser

ARG PUPPETEER_VERSION=25.14.0

ENV PUPPETEER_CACHE_DIR=/browser

RUN npm install --global "puppeteer@${PUPPETEER_VERSION}" && \
    test -d /browser/chrome

FROM serversideup/php:8.5-fpm-nginx

USER root

# intl/bcmath/exif for Laravel, gd/imagick for spatie/laravel-medialibrary, excimer for profiling.
# zip, redis, pdo_mysql, pcntl and opcache already ship with the base image.
RUN install-php-extensions intl bcmath exif gd imagick excimer

# git + ghostscript for source installs and PDF/imagick processing,
# Chromium's shared libraries for Browsershot/Puppeteer, and Node.js 24 for Vite.
RUN apt-get update && \
    apt-get install -yq --no-install-recommends \
        git \
        ghostscript \
        libasound2 \
        libatk-bridge2.0-0 \
        libatk1.0-0 \
        libatspi2.0-0 \
        libc6 \
        libcairo2 \
        libcups2 \
        libdbus-1-3 \
        libdrm2 \
        libexpat1 \
        libgbm1 \
        libglib2.0-0 \
        libnspr4 \
        libnss3 \
        libpango-1.0-0 \
        libpangocairo-1.0-0 \
        libstdc++6 \
        libudev1 \
        libuuid1 \
        libx11-6 \
        libx11-xcb1 \
        libxcb-dri3-0 \
        libxcb1 \
        libxcomposite1 \
        libxcursor1 \
        libxdamage1 \
        libxext6 \
        libxfixes3 \
        libxi6 \
        libxkbcommon0 \
        libxrandr2 \
        libxrender1 \
        libxshmfence1 \
        libxss1 \
        libxtst6 \
    && \
    curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -yq --no-install-recommends nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html

# One-shot start-up scripts, executed by the base image entrypoint in numeric order.
COPY --chmod=755 ./entrypoint.d/ /etc/entrypoint.d/

# Long-running Nightwatch agent, registered in the S6 "user" bundle.
COPY --chmod=755 ./docker/s6-overlay/s6-rc.d/ /etc/s6-overlay/s6-rc.d/
COPY --chmod=755 ./docker/s6-overlay/user-bundles.d/ /etc/s6-overlay/user-bundles.d/

USER www-data

# www-data's home is /var/www. Pinning it (and the Puppeteer cache) makes the
# build and the runtime agree on where Node and the browser live.
ENV HOME="/var/www" \
    PUPPETEER_CACHE_DIR="/var/www/.cache/puppeteer"

# Browser cache, copied from the pinned stage above.
COPY --from=browser --chown=www-data:www-data /browser /var/www/.cache/puppeteer

# Dependencies first, so they stay cached across application code changes.
COPY --chown=www-data:www-data composer.json composer.lock ./

RUN --mount=type=cache,target=/composer/cache,uid=33,gid=33,mode=0775 \
    composer install --no-dev --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts --classmap-authoritative

COPY --chown=www-data:www-data package.json package-lock.json ./

# Puppeteer skips its own download, the browser comes from the stage above.
RUN --mount=type=cache,target=/var/www/.npm,uid=33,gid=33,mode=0775 \
    PUPPETEER_SKIP_DOWNLOAD=true npm ci --no-audit --no-fund

COPY --chown=www-data:www-data . .

# The app source is now present, so run package discovery and Filament's upgrade.
# The throwaway key keeps artisan bootable now that .env is no longer baked into the image.
RUN APP_KEY="base64:$(head -c 32 /dev/urandom | base64 -w0)" \
    composer dump-autoload --classmap-authoritative --no-dev && \
    rm -f bootstrap/cache/config.php

# Build the assets, then drop the build-only dependencies from the runtime image.
RUN npm run build && \
    PUPPETEER_SKIP_DOWNLOAD=true npm prune --omit=dev --no-audit --no-fund

ENV AUTORUN_ENABLED="true" \
    AUTORUN_LARAVEL_MIGRATION_ISOLATION="true" \
    AUTORUN_LARAVEL_MIGRATION_TIMEOUT="60" \
    \
    PHP_OPCACHE_ENABLE="1" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="64" \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER="16" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="50000" \
    \
    PHP_FPM_PM_CONTROL="dynamic" \
    PHP_FPM_PM_MAX_CHILDREN="10" \
    PHP_FPM_PM_START_SERVERS="2" \
    PHP_FPM_PM_MIN_SPARE_SERVERS="2" \
    PHP_FPM_PM_MAX_SPARE_SERVERS="5" \
    PHP_FPM_PM_MAX_REQUESTS="500" \
    \
    PHP_MEMORY_LIMIT="512M" \
    PHP_MAX_EXECUTION_TIME="120" \
    PHP_POST_MAX_SIZE="200M" \
    PHP_UPLOAD_MAX_FILE_SIZE="200M" \
    \
    NGINX_FASTCGI_BUFFERS="16 16k" \
    NGINX_FASTCGI_BUFFER_SIZE="32k" \
    \
    NGINX_CLIENT_MAX_BODY_SIZE="200M" \
    \
    TRUSTED_PROXY="cloudflare" \
    SHOW_WELCOME_MESSAGE="false" \
    \
    HEALTHCHECK_PATH="/up"
