# Use official PHP-Apache image
FROM php:8.2-apache

# Enable mod_rewrite for CodeIgniter
RUN a2enmod rewrite

# Install system dependencies and PHP extensions
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        curl \
        git \
    && docker-php-ext-install pdo pdo_mysql mysqli zip \
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

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/writable

# Configure Apache
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]