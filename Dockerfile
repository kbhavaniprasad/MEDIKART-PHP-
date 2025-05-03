# Use the official PHP image with Apache
FROM php:8.4-apache

# Install MySQLi extension
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Copy your PHP files into the container
COPY . /var/www/html/

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Enable Apache mod_rewrite (optional, keep if needed)
RUN a2enmod rewrite