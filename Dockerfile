# Use the serversideup/php:8.4-cli image as the base image.
FROM serversideup/php:8.4-cli AS vendor

USER root

# Install PHP dependencies.
RUN install-php-extensions intl bcmath exif

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY app /app/app
COPY database /app/database

COPY composer.json /app/composer.json
COPY composer.lock /app/composer.lock

WORKDIR /app

RUN composer install --prefer-dist --no-ansi --no-interaction --no-progress --classmap-authoritative

##############################################################################

FROM node:23-alpine AS assets

RUN mkdir -p /app/public

COPY package.json vite.config.js package-lock.json /app/

COPY resources/ /app/resources/

COPY --chown=www-data:www-data --from=vendor /app/vendor/ /app/vendor/

WORKDIR /app

RUN npm install && npm run build

##############################################################################

FROM serversideup/php:8.4-fpm-nginx

USER www-data

# Copies the Laravel app, but skips the ignored files and paths
COPY --chown=www-data:www-data . /var/www/html
COPY --chown=www-data:www-data --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=www-data:www-data --from=vendor /app/public/css/ /var/www/html/public/css/
COPY --chown=www-data:www-data --from=vendor /app/public/js/ /var/www/html/public/js/
COPY --chown=www-data:www-data --from=assets /app/public/build/ /var/www/html/public/build/

ENV AUTORUN_ENABLED="true" \
    # AUTORUN_LARAVEL_MIGRATION="true" \
    # AUTORUN_LARAVEL_MIGRATION_ISOLATION="true" \
    PHP_OPCACHE_ENABLE="1"
