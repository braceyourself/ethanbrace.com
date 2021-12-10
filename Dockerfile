FROM php:8.0-fpm

WORKDIR /var/www/html

ADD . /var/www/html

ENV stage=dev

