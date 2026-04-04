FROM composer:latest as composer
WORKDIR "/app"
COPY composer.json composer.lock symfony.lock ./

# composer install ausführen jedcoh ohne scripts und autloader
# da noch nicht die ganze soure reinkopiert wurde. Grund: Caching
# Wenn die Source bereits am Anfang reinkopiert würde, würde bei jeder Codeänderung 
# bei Rebuild composer install erneute ausgeführt werden und könnte nicht vom Cache geladen werden.

RUN composer install --no-scripts --no-dev --ignore-platform-req=ext-gd

FROM nginx:1.28-alpine
EXPOSE 80
EXPOSE 443
WORKDIR /var/www


# alpine removes packages!: https://medium.com/@stschindler/the-problem-with-docker-and-alpines-package-pinning-18346593e891
#ENV php8version 8.4.x
RUN apk --update add --no-cache \
	php84  \
	php84-fpm  \
	php84-dom  \
	php84-tokenizer  \
	php84-pdo  \
	php84-pdo_pgsql  \
	php84-mbstring  \
	php84-openssl  \
	php84-xml  \
	php84-xmlwriter \
	php84-xmlreader \
	php84-fileinfo \
	php84-simplexml  \
	php84-session  \
	php84-zip  \
	php84-gd \
	php84-phar

RUN apk --update add --no-cache certbot
# RUN /usr/bin/certbot certonly --webroot --webroot-path /var/www/public --agree-tos -m personal@burgdorfer-stadtlauf.ch -d helfer.burgdorfer-stadtlauf.ch --no-eff-email

COPY ./resources/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./resources/php/php.ini /etc/php84/php.ini
COPY ./resources/php/zz-docker-env.conf /etc/php84/php-fpm.d/zz-docker-env.conf
COPY --from=composer /app /var/www
COPY . .

RUN ./bin/console cache:warmup --env=prod
RUN ./bin/console assets:install --env=prod

RUN chmod a+x ./resources/run_nginx_php.sh
RUN chmod a+x ./resources/run_web.sh

# Start via sh so bind-mounted scripts also work without an executable bit.
CMD ["sh", "./resources/run_web.sh"]
