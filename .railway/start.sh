#!/bin/bash

# Railway Startup Script for Laravel

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Clear and cache configs
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Start the application
php artisan serve --host=0.0.0.0 --port=$PORT
