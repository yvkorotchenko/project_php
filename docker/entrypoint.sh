#!/bin/sh

/usr/local/sbin/php-fpm -D; cd /var/www/html; php artisan key:generate; php artisan migrate; php artisan config:clear; php artisan cache:clear; php artisan route:clear; php artisan view:clear; php artisan storage:link;  sleep 10; chown www-data: /var/www/html/storage/logs/laravel.log; chown www-data: /var/www/html/storage/framework/cache/data; chmod -R 777 /var/www/html/database; nginx -g 'daemon off;'

exec "$@"
