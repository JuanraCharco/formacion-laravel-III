FROM php:8.2-fpm
RUN apt-get update
RUN apt-get install -y gnupg curl wget ca-certificates unzip lsb-release
RUN wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | apt-key add -
RUN echo "deb http://apt.postgresql.org/pub/repos/apt/ `lsb_release -cs`-pgdg main" | tee  /etc/apt/sources.list.d/pgdg.list
RUN apt-get install -y libicu-dev libpq-dev libzip-dev
RUN apt-get install -y nodejs
RUN apt-get install -y postgresql-client postgis
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install intl pdo pdo_pgsql pgsql zip bcmath pcntl exif
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
RUN apt-get install -y npm
RUN apt-get update
RUN apt-get -y autoremove
RUN apt-get clean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN chown -R www-data:www-data /var/www
WORKDIR /var/www/app
USER root
