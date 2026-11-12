# PHP 8.5 with FPM, as on the server (Plesk: alt-php85-fpm served by Apache)
FROM php:8.5-fpm

RUN docker-php-ext-install pdo_mysql \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# display_errors Off and log_errors On, as in Plesk; errors go to `docker compose logs php`
COPY ibf.ini "$PHP_INI_DIR/conf.d/ibf.ini"
