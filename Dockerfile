# Use the official PHP image with Apache
FROM php:8.4-apache

# Copy your PHP files into the container
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite
