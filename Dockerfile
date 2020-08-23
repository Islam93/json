ARG PHP_VERSION='7.3-rc'
FROM php:$PHP_VERSION
RUN apt-get update \
    && apt-get install -y git unzip
WORKDIR /usr/src/json
RUN curl -sS https://getcomposer.org/installer | php && mv ./composer.phar /usr/local/bin/composer
COPY . /usr/src/json
RUN composer install
