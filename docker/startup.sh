#!/bin/sh

sed -i "s,LISTEN_PORT,$PORT,g" /etc/nginx/nginx.conf

cd /app

mkdir -p \
    storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    storage/logs \
    storage/app/public \
    storage/app/private \
    bootstrap/cache \
    database

if [ "$DB_CONNECTION" = "sqlite" ]; then
    mkdir -p /app/database
    touch /app/database/database.sqlite
    
    chown -R www-data:www-data /app/database
    chown www-data:www-data /app/database/database.sqlite
    
    chmod 755 /app/database
    chmod 664 /app/database/database.sqlite
fi

# Set other permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

php artisan migrate --force

php-fpm -D
while ! nc -w 1 -z 127.0.0.1 9000; do sleep 0.1; done;

nginx
