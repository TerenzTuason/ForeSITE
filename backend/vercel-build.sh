#!/bin/bash

# 1. Install dependencies
composer install --no-interaction --no-dev --prefer-dist

# 2. Run framework optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. (Optional) Run database migrations
# Note: Be cautious with running migrations in a serverless environment build step.
# It's often better to run them from your local machine or a separate pipeline
# that connects to your production database.
#
# If you must run them here, uncomment the line below.
# php artisan migrate --force 