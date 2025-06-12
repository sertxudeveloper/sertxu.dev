FROM sertxudeveloper/laravel-php8.4:latest

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

USER www-data

RUN composer install --prefer-dist --no-ansi --no-interaction --no-progress --classmap-authoritative

RUN npm install && \
    npm run build

ENV AUTORUN_ENABLED="true" \
    # AUTORUN_LARAVEL_MIGRATION="true" \
    # AUTORUN_LARAVEL_MIGRATION_ISOLATION="true" \
    PHP_OPCACHE_ENABLE="1"
