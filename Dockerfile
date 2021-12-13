FROM php:8.0-fpm AS base

WORKDIR /var/www/html

ARG WWWUSER=1000
ARG WWWGROUP=1000

RUN groupmod -g $WWWGROUP www-data \
    && usermod -u $WWWUSER www-data

RUN chown www-data:www-data /var/www -R

USER www-data


FROM base AS prod

ADD . /var/www/html

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions \
        @composer

RUN composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --no-dev \
    && npm install \
    && npm run prod \
    && rm -rf node_modules \
    && /var/www/html/artisan storage:link \


