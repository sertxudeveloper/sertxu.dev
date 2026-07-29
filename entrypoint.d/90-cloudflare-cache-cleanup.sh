#!/bin/sh

# Global configurations
: "${APP_BASE_DIR:=/var/www/html}"

php "$APP_BASE_DIR/artisan" cloudflare:purge --all --force
