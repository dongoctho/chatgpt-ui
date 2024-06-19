FROM php:8.3-apache

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Change document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable rewrite mod
RUN a2enmod rewrite

# Install required commands
RUN apt update && \
    apt install -y zip unzip git && \
    rm -rf /var/lib/apt/lists/*

# Install required php libraries
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions intl pdo_pgsql
