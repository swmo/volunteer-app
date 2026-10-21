FROM composer:latest AS composer
WORKDIR "/app"
COPY composer.json composer.lock symfony.lock ./

# composer install ausführen jedcoh ohne scripts und autloader
# da noch nicht die ganze soure reinkopiert wurde. Grund: Caching
# Wenn die Source bereits am Anfang reinkopiert würde, würde bei jeder Codeänderung 
# bei Rebuild composer install erneute ausgeführt werden und könnte nicht vom Cache geladen werden.

RUN composer install --no-scripts --no-dev --ignore-platform-req=ext-gd

FROM php:8.5.12-fpm-alpine3.23
WORKDIR /var/www

RUN apk add --no-cache freetype libjpeg-turbo libpng libpq libzip \
    && apk add --no-cache --virtual .php-build-deps \
        $PHPIZE_DEPS freetype-dev libjpeg-turbo-dev libpng-dev postgresql-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" gd pdo_pgsql zip \
    && apk del .php-build-deps

COPY ./resources/php/php.ini /usr/local/etc/php/php.ini
COPY ./resources/php/zz-docker-env.conf /usr/local/etc/php-fpm.d/zz-docker-env.conf
COPY --from=composer /app /var/www
COPY . .

RUN ./bin/console cache:warmup --env=prod
RUN ./bin/console assets:install --env=prod
RUN mkdir -p /var/www/var/cache/dev/doctrine/orm/Proxies /var/www/var/log
RUN chmod -R a+rwX /var/www/var

EXPOSE 9000

CMD ["php-fpm", "-F"]
