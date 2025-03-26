# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set working directory inside the container
WORKDIR /var/www/html

# Copy project files to container
COPY . /var/www/html

# Set writable permissions for CodeIgniter
RUN chmod -R 777 /var/www/html/writable

# Install Composer
RUN apt-get update && apt-get install -y curl unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
