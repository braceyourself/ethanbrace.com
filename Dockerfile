FROM php:8.0-fpm

WORKDIR /var/www/html

ARG WWWUSER=1000
ARG WWWGROUP=1000

RUN groupmod -g $WWWGROUP www-data \
    && usermod -u $WWWUSER www-data

ADD . /var/www/html

RUN chown www-data:www-data /var/www -R

USER www-data

RUN /var/www/html/artisan storage:link
