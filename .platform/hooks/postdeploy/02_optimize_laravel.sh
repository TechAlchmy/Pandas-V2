#!/bin/bash

# Optimizing configuration loading, route loading and view loading
# https://laravel.com/docs/9.x/deployment#optimization



php artisan optimize:clear

# don't optimize in case of staging server

if [ "$APP_ENV" == "production" ]
then
    /usr/bin/composer.phar install --no-dev --no-interaction --optimize-autoloader
    php artisan config:cache
    php artisan view:cache

    php artisan route:cache
    php artisan route:cache
    # we are doing this twice since doing it only once does not cache the routes properly in this setup
    # php artisan make:admin
else
    php artisan migrate --force
    /usr/bin/composer.phar install --no-interaction --optimize-autoloader
    echo "Optimization is not run in staging environment!"
fi
