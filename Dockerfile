# Use the serversideup/php:8.4-fpm-nginx image as the base image.
FROM serversideup/php:8.4-fpm-nginx as base

USER root

RUN install-php-extensions intl bcmath exif

# Copy the application files to the container.
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies using Composer.
# Use --no-scripts to prevent Composer from running scripts during the install process.
# This is often safer in Dockerfiles, as it prevents potential issues with missing
# dependencies or environment configurations.  We'll run the necessary artisan
# commands (key:generate, migrate) explicitly later.
RUN composer install --no-scripts --no-interaction --prefer-dist

ENV SSL_MODE="off" \
    PHP_OPCACHE_ENABLE="1" \
    AUTORUN_ENABLED="true"

USER www-data
