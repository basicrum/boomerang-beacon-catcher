FROM php:7-fpm-alpine

RUN apk add --update bash \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && rm -rf /var/cache/apk/* \
    && sed -i "s/;access.format/access.format/g" /usr/local/etc/php-fpm.d/www.conf