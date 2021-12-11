FROM php:8.0-fpm

WORKDIR /var/www/html

ADD --chown=1000:1000 . /var/www/html

