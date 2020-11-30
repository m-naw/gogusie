FROM php:7.4-fpm-alpine

RUN apk add --update \
    make \
    curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    ln -s $(composer config --global home) /root/composer
ENV PATH=$PATH:/root/composer/vendor/bin COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/www/gog

EXPOSE 9000

CMD ["php-fpm", "-F"]