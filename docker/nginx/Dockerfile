FROM nginx:alpine

RUN apk add --update bash \
    && rm -rf /var/cache/apk/*

COPY *.conf /etc/nginx/conf.d/