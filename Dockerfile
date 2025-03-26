# Use official PHP-Apache image
FROM php:8.2-apache

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set writable permissions for CodeIgniter
RUN chmod -R 777 /var/www/html/writable

# Install Composer and dependencies
RUN apt-get update && apt-get install -y curl unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader

# Expose Apache on port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
