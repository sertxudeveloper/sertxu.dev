#!/bin/sh

# Global configurations
: "${APP_BASE_DIR:=/var/www/html}"

artisan_migrate() {
    echo "🚀 Clearing Laravel cache before attempting migrations..."
    php "$APP_BASE_DIR/artisan" config:clear

    echo "🚀 Running migrations for landlord database"
    php "$APP_BASE_DIR/artisan" migrate --force --isolated --database=landlord --path="database/migrations/landlord"

    echo "🚀 Running migrations for tenants databases"
    php "$APP_BASE_DIR/artisan" tenants:artisan "migrate --force --isolated" || echo "⚠️ No tenants found or migration failed, continuing..."
}

artisan_optimize() {
    echo "🚀 Starting Laravel optimizations..."

    php "$APP_BASE_DIR/artisan" optimize
}

############################################################################
# Main
############################################################################

if true; then
    echo "🤔 Checking for Laravel automations..."

    artisan_migrate
    artisan_optimize
fi
