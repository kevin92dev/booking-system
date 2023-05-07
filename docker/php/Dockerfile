FROM php:8.2-fpm

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY . /var/www

# DEV
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug
COPY docker/php/php-conf/* /usr/local/etc/php/conf.d/

WORKDIR /var/www

RUN composer install --no-interaction --no-progress --prefer-dist