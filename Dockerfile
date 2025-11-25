FROM sertxudeveloper/laravel-php8.4:latest

COPY --chown=www-data:www-data . /var/www/html

WORKDIR /var/www/html

USER root

RUN set -xe; \
    mkdir -p /etc/s6-overlay/s6-rc.d/laravel-nightwatch; \
    echo "longrun" > /etc/s6-overlay/s6-rc.d/laravel-nightwatch/type; \
    echo "#!/command/execlineb -P" > /etc/s6-overlay/s6-rc.d/laravel-nightwatch/run; \
    echo "php /var/www/html/artisan nightwatch:agent" >> /etc/s6-overlay/s6-rc.d/laravel-nightwatch/run; \
    touch /etc/s6-overlay/s6-rc.d/user/contents.d/laravel-nightwatch; \
    mkdir -p /etc/s6-overlay/s6-rc.d/laravel-nightwatch/dependencies.d; \
    touch /etc/s6-overlay/s6-rc.d/laravel-nightwatch/dependencies.d/base

USER www-data

RUN composer install --prefer-dist --no-ansi --no-interaction --no-progress --classmap-authoritative

RUN php artisan octane:install --server=frankenphp

RUN npm install && \
    npm run build

ENV AUTORUN_ENABLED="true" \
    # AUTORUN_LARAVEL_MIGRATION="true" \
    # AUTORUN_LARAVEL_MIGRATION_ISOLATION="true" \
    PHP_OPCACHE_ENABLE="1" \
    \
    NGINX_CLIENT_MAX_BODY_SIZE: "200M" \
    NGINX_FASTCGI_BUFFERS: "32 32k" \
    \
    PHP_FPM_PM_CONTROL: "static" \
    PHP_FPM_PM_MAX_CHILDREN: "50" \
    PHP_MEMORY_LIMIT: "512M"

