FROM serversideup/php:8.4-fpm-nginx

USER root

RUN install-php-extensions intl bcmath exif

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

USER www-data

ENV NODE_ENV=production

RUN composer install --prefer-dist --no-ansi --no-interaction --no-progress --classmap-authoritative

RUN apt-get update && apt-get install -y nodejs npm

RUN npm install && npm run build

ENV AUTORUN_ENABLED="true" \
    # AUTORUN_LARAVEL_MIGRATION="true" \
    # AUTORUN_LARAVEL_MIGRATION_ISOLATION="true" \
    PHP_OPCACHE_ENABLE="1"
