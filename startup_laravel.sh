#!/bin/sh
# startup_laravel.sh

# if /var/www/html/vendor is empty, copy /var/www/vendor to /var/www/html/vendor
if [ -z "$(ls -A /var/www/html/vendor)" ]; then cp -r /var/www/vendor /var/www/html; fi;

# delete all log files in /storage/logs
rm -rf /var/www/html/storage/logs/*.log;

# create .env file if not exists and generate key (only if .env file does not exist)
if [ ! -f /var/www/html/.env ]; then cp /var/www/html/.env.example /var/www/html/.env
    php artisan key:generate; fi;

# make db-migration-wait.sh executable
# start laravel with supervisord
chmod +x /var/www/html/db-migration-wait.sh;
supervisord -c supervisord.conf;
