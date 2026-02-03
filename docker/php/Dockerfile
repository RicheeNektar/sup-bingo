FROM php:8.4-fpm-alpine

RUN apk add --no-cache $PHPIZE_DEPS linux-headers && \
    pecl install xdebug && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo pdo_mysql

EXPOSE 9000
CMD ["php-fpm"]