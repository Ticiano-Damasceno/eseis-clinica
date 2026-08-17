FROM php:8.3.8-apache

# Extensões que o Laravel + MariaDB exigem
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip

# Habilita mod_rewrite (necessário pro roteamento do Laravel)
RUN a2enmod rewrite

# Composer (copiado da imagem oficial, não instalado via script)
COPY --from=composer:2.8.6 /usr/bin/composer /usr/bin/composer

ARG UID=1000
ARG GID=1000
RUN groupmod -g ${GID} www-data && usermod -u ${UID} -g ${GID} www-data

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html