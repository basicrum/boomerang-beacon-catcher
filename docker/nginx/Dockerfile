FROM nginx:1.16.0-alpine

RUN apk add --update bash \
    && rm -rf /var/cache/apk/*

COPY ./docker/nginx/*.conf /etc/nginx/conf.d/
COPY ./app/public /usr/src/app