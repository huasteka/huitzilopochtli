# syntax=docker/dockerfile:1

FROM php:7.4-fpm-alpine

RUN apk add --update libpq-dev && docker-php-ext-install pgsql pdo_pgsql

RUN apk add --update nodejs npm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

ENV APP_HOME=/home/huitzilopochtli/app

RUN mkdir -p ${APP_HOME}

WORKDIR ${APP_HOME}

COPY . .

RUN composer install

RUN npm install && npm cache clean --force

EXPOSE 9704

CMD ["php", "-S", "0.0.0.0:9704", "-t", "public"]
