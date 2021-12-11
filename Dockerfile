FROM php:8.0-fpm

WORKDIR /var/www/html

ADD --chown=www-data:www-data . /var/www/html

RUN chown www-data:www-data /var/www/html -R

USER www-data

