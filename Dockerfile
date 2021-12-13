FROM php:8.0-fpm AS base

WORKDIR /var/www/html

ARG WWWUSER=1000
ARG WWWGROUP=1000

RUN groupmod -g $WWWGROUP www-data \
    && usermod -u $WWWUSER www-data



FROM base AS prod


COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions \
        @composer

RUN apt-get update && apt-get install -y \
    software-properties-common \
    npm

RUN npm install npm@latest -g \
    && npm install n -g \
    && n lts


USER www-data
ADD . /var/www/html

RUN chown www-data:www-data /var/www -R

RUN composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --no-dev \
    && npm install \
    && npm run prod \
    && rm -rf node_modules \
    && /var/www/html/artisan storage:link


