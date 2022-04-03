FROM composer:latest as composer
WORKDIR "/app"
COPY composer.json composer.lock symfony.lock ./

# composer install ausführen jedcoh ohne scripts und autloader
# da noch nicht die ganze soure reinkopiert wurde. Grund: Caching
# Wenn die Source bereits am Anfang reinkopiert würde, würde bei jeder Codeänderung 
# bei Rebuild composer install erneute ausgeführt werden und könnte nicht vom Cache geladen werden.

RUN composer install --no-scripts --no-dev --ignore-platform-reqs

FROM nginx:1.17.8-alpine-perl
EXPOSE 80
EXPOSE 443


# alpine removes packages!: https://medium.com/@stschindler/the-problem-with-docker-and-alpines-package-pinning-18346593e891
#ENV php7version 7.3.8-r0
RUN apk --update add --no-cache \
	php7  \
	php7-dom  \
	php7-fpm  \
	php7-ctype  \
	php7-json  \
	php7-pdo  \
	php7-tokenizer  \
	php7-session  \
	php7-mbstring  \
	php7-iconv  \
	php7-xml  \
	php7-simplexml  \
	php7-pgsql  \
	php7-openssl  \
	php7-pdo_pgsql  \
	php7-zip  \
	php7-gd 

RUN apk --update add --no-cache \
	php7-xmlwriter \
	php7-fileinfo \
	php7-xmlreader

RUN apk --update add --no-cache certbot
# RUN /usr/bin/certbot certonly --webroot --webroot-path /var/www/public --agree-tos -m personal@burgdorfer-stadtlauf.ch -d helfer.burgdorfer-stadtlauf.ch --no-eff-email

COPY ./resources/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY ./resources/php/php.ini /etc/php7/php.ini
COPY --from=composer /app /var/www
COPY . ./var/www

RUN  ./var/www/bin/console cache:warmup --env=prod
RUN  ./var/www/bin/console assets:install --env=prod

RUN chmod a+x ./var/www/resources/run_nginx_php.sh

#CMD ./var/www/docker/run_nginx_php.sh
CMD ["./var/www/resources/run_nginx_php.sh"]
