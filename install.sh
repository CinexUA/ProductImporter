#!/bin/bash

docker exec dcounter-php sh -c 'cd /var/www/html && \
composer clearcache && \
composer install && \
php artisan cache:clear && \
php artisan route:clear && \
php artisan route:trans:clear && \
php artisan view:clear && \
php artisan clear-compiled && \
chown -R www.www /var/www/html/app/ && \
chown -R www.www /var/www/html/storage/ && \
chown -R www.www /var/www/html/tests/ && \
chown -R www.www /var/www/html/vendor/ && \
chown -R www.www /var/www/html/public/ && \
chown -R www.www /var/www/html/resources/ && \
chown -R www.www /var/www/html/routes/ && \
chown -R www.www /var/www/html/bootstrap/ && \
chown -R www.www /var/www/html/config/ && \
chown -R www.www /var/www/html/database/ && \
php artisan migrate'