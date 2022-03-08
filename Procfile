web: vendor/bin/heroku-php-apache2 public/
worker: php artisan queue:restart && php artisan queue:work database --tries=3
scheduler: php -d memory_limit=512M artisan schedule:systemChange

