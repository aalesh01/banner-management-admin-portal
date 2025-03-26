# Use official PHP-Apache image
FROM php:8.2-apache

# Install intl extension and dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && docker-php-ext-install intl \
    && apt-get clean

# Install PostgreSQL dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean

# Set Apache ServerName to prevent warnings
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Enable required Apache modules
RUN a2enmod rewrite headers

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        curl \
        git \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mysqli \
        zip \
        gd \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first to cache dependencies
COPY composer.json composer.lock ./

# Install dependencies (ignore platform reqs for better compatibility)
RUN composer install --no-dev --no-interaction --optimize-autoloader --ignore-platform-reqs

# Copy the rest of the application
COPY . .

# Set permissions (adjust paths according to your framework)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/writable \
    && chmod +x /var/www/html/public/index.php

# Configure Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable production settings for PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

EXPOSE 80

CMD ["apache2-foreground"]