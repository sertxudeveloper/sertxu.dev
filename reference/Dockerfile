FROM serversideup/php:8.5-fpm-nginx

USER root

# Install PHP extensions
RUN install-php-extensions intl bcmath exif gd imagick excimer

# Install system dependencies: Git, Ghostscript
# Combined into a single layer to reduce image size
RUN apt-get update && \
    apt-get install -yq --no-install-recommends \
        git \
        ghostscript \
        wget \
        gnupg \
        ca-certificates \
        libcairo2 \
        libpango-1.0-0 \
        libpangoft2-1.0-0 \
        libgdk-pixbuf-2.0-0 \
        libffi-dev \
        shared-mime-info \
        python3-pip \
        fonts-inter \
        cups-client \
    && \
    curl -fsSL https://deb.nodesource.com/setup_24.x | bash - && \
    apt-get install -y --no-install-recommends nodejs && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# If you have your own one-shot scripts, copy them to the entrypoint.d directory
COPY --chmod=755 ./entrypoint.d/50-laravel-automations.sh /etc/entrypoint.d/

# Initialize S6 Overlay (moves entrypoint.d scripts to s6-overlay scripts directory)
RUN docker-php-serversideup-s6-init

# Copy custom S6 service for Pulse monitoring
COPY --chmod=755 ./docker/s6-overlay/s6-rc.d/ /etc/s6-overlay/s6-rc.d/
COPY --chmod=755 ./docker/s6-overlay/user-bundles.d/ /etc/s6-overlay/user-bundles.d/

USER www-data

COPY --chown=www-data:www-data composer.json composer.lock /var/www/html/
COPY --chown=www-data:www-data resources/helpers.php /var/www/html/resources/

WORKDIR /var/www/html

# Configure Flux UI auth and install dependencies using cache mounts
RUN --mount=type=secret,id=flux_username,env=FLUX_USERNAME \
    --mount=type=secret,id=flux_license_key,env=FLUX_LICENSE_KEY \
    --mount=type=cache,target=/home/www-data/.composer/cache \
    composer config --auth http-basic.composer.fluxui.dev "$FLUX_USERNAME" "$FLUX_LICENSE_KEY" && \
    composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-dev && \
    composer clear-cache

COPY --chown=www-data:www-data package.json package-lock.json /var/www/html/

# Install Node dependencies
RUN --mount=type=cache,target=/home/www-data/.npm \
    npm ci && \
    npm cache clean --force

COPY --chown=www-data:www-data . /var/www/html

# Regenerate autoloader to include application classes
RUN composer dump-autoload --classmap-authoritative --no-dev

# Add release version SHA
RUN --mount=type=secret,id=release,env=RELEASE \
    sed -i "s/intranet@/intranet@$RELEASE/" config/sentry.php

# Make APP_VERSION available as an environment variable
ARG APP_VERSION
ENV APP_VERSION=$APP_VERSION

# Build frontend assets
RUN npm run build

ENV PHP_OPCACHE_ENABLE="1" \
    PHP_FPM_PM_CONTROL="static" \
    PHP_FPM_PM_MAX_CHILDREN="50" \
    PHP_MEMORY_LIMIT="512M" \
    PHP_REALPATH_CACHE_SIZE="8192K" \
    \
    NGINX_ACCESS_LOG="/dev/null" \
    NGINX_CLIENT_MAX_BODY_SIZE="200M" \
    NGINX_FASTCGI_BUFFERS="32 32k" \
    \
    TRUSTED_PROXY="local" \
    \
    HEALTHCHECK_PATH="/up"
