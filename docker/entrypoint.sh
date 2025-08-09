#!/bin/bash
set -e

# Generate application key if not exists
if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
    php artisan key:generate
fi

# Run migrations
php artisan migrate --force

# Seed database if empty
php artisan db:seed --force

# Cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf