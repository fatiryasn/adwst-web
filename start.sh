#!/bin/bash

php artisan storage:link

php artisan migrate --force

php-fpm -D

nginx -g "daemon off;"