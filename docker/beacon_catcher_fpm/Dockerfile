FROM php:7.3.4-fpm-alpine

RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow && usermod -u 1000 www-data 

RUN apk add --update bash \
    && rm -rf /var/cache/apk/*

RUN sed -i "s/;access.format/access.format/g" /usr/local/etc/php-fpm.d/www.conf

RUN usermod -u 82 www-data

RUN mkdir "/var/tmp/beacons"
RUN chmod  777 "/var/tmp/beacons"

RUN chown -R www-data:www-data /var/tmp/beacons