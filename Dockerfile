FROM php:8.2-apache

# set user and group
ARG user=www-data
ARG group=${user}

# set root path for apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

COPY ./config/docker-php.conf /etc/apache2/conf-available/docker-php.conf

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# update packages
RUN apt update \
    && apt dist-upgrade -y \
    && apt install git zlib1g-dev libzip-dev unzip -y

RUN docker-php-ext-install zip

# add composer binary from the official image
COPY --from=composer /usr/bin/composer /usr/bin/composer

# copy all files from the project into the docker container
COPY --chown=${user}:${group} ./app /var/www/html

WORKDIR /var/www/html

# install composer packages
RUN composer install