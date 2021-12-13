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


ADD . /var/www/html
RUN mkdir -p /var/www/html/public/vendor/statamic/cp
RUN chown www-data:www-data /var/www -R

USER www-data

RUN composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist --no-dev \
    && /var/www/html/artisan storage:link


RUN cp -r /var/www/html/vendor/statamic/cms/resources/dist/** /var/www/html/public/vendor/statamic/cp/
