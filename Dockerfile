FROM php:7.0-apache

RUN apt-get update && \
apt-get install -y zlib1g-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev mysql-server && \
rm -rf /var/lib/apt/lists/*

RUN docker-php-source extract && \
docker-php-ext-configure gd --with-freetype-dir --with-jpeg-dir && \
docker-php-ext-install gd mysqli && \
docker-php-source delete

RUN mysql_install_db

COPY --chown=www-data:www-data perf /var/www/html
COPY init-db /usr/bin
COPY start-db /usr/bin

CMD start-db && apache2-foreground
