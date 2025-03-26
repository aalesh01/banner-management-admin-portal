# Use official PHP-Apache image
FROM php:8.2-apache

# Enable mod_rewrite for CodeIgniter
RUN a2enmod rewrite

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html

# Set correct permissions for Apache & CodeIgniter
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN chmod -R 777 /var/www/html/writable

# Install Composer and dependencies
RUN apt-get update && apt-get install -y curl unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer install --no-dev --optimize-autoloader

# Set Apache document root to the public folder (important for CodeIgniter)
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# Expose Apache on port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
