FROM php:7.2-fpm-alpine

# Dependencies to use phpize which is required for pecl install
RUN apk add --no-cache \
		$PHPIZE_DEPS

# MySQL
RUN docker-php-ext-install pdo pdo_mysql

# xdebug
RUN pecl install xdebug-2.6.0
RUN docker-php-ext-enable xdebug
ENV PHP_IDE_CONFIG="serverName=skeleton.local"

ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

