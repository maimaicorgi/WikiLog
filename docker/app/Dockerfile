FROM php:fpm-bullseye
WORKDIR /var/www/html

# PHPで必要なライブラリ・ドライバ
RUN apt-get update \
    && apt-get install -y libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# composerのインストール
COPY --from=composer /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_NO_INTERACTION 1

# ファイルのコピー
COPY ./docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
