FROM php:8.1-fpm AS base

WORKDIR /var/www/html

ARG WWWUSER=1000
ARG WWWGROUP=1000

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

ADD . /var/www/html

RUN groupmod -g $WWWGROUP www-data \
    && usermod -u $WWWUSER www-data

RUN apt update && apt install -y rsync

RUN install-php-extensions \
        @composer \
    &&  composer install -q \
        --no-interaction \
        --no-scripts \
        --prefer-dist

USER www-data

#------------------------------
# compile assets
#------------------------------
FROM node:14.15.5 AS assets

WORKDIR /usr/src/app

COPY . .

RUN npm install && \
    npm run prod


#------------------------------
# build prod image
#------------------------------
FROM base AS prod

USER root

COPY --from=assets /usr/src/app/public /var/www/html/dist


RUN mkdir -p /var/www/html/public/vendor/statamic/cp \
    && chown www-data:www-data /var/www -R

USER www-data

RUN composer install -q \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --no-dev \
    && composer dump-autoload \
    && /var/www/html/artisan storage:link \
    && cp -r /var/www/html/vendor/statamic/cms/resources/dist/** /var/www/html/public/vendor/statamic/cp/ \
    && rsync -a dist/ public/
