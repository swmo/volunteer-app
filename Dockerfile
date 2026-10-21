FROM composer:latest as composer
WORKDIR "/app"
COPY composer.json composer.lock symfony.lock ./

# composer install ausführen jedcoh ohne scripts und autloader
# da noch nicht die ganze soure reinkopiert wurde. Grund: Caching
# Wenn die Source bereits am Anfang reinkopiert würde, würde bei jeder Codeänderung 
# bei Rebuild composer install erneute ausgeführt werden und könnte nicht vom Cache geladen werden.

RUN composer install --no-scripts --no-dev --ignore-platform-req=ext-gd

FROM alpine:3.23
WORKDIR /var/www

RUN apk --update add --no-cache \
	php85  \
	php85-fpm  \
	php85-iconv  \
	php85-dom  \
	php85-tokenizer  \
	php85-pdo  \
	php85-pdo_pgsql  \
	php85-mbstring  \
	php85-openssl  \
	php85-xml  \
	php85-xmlwriter \
	php85-xmlreader \
	php85-fileinfo \
	php85-simplexml  \
	php85-session  \
	php85-zip  \
	php85-gd \
	php85-phar
RUN ln -sf /usr/bin/php85 /usr/bin/php

COPY ./resources/php/php.ini /etc/php85/php.ini
COPY ./resources/php/zz-docker-env.conf /etc/php85/php-fpm.d/zz-docker-env.conf
COPY --from=composer /app /var/www
COPY . .

RUN ./bin/console cache:warmup --env=prod
RUN ./bin/console assets:install --env=prod
RUN mkdir -p /var/www/var/cache/dev/doctrine/orm/Proxies /var/www/var/log
RUN chmod -R a+rwX /var/www/var

EXPOSE 9000

CMD ["php-fpm85", "-F"]
