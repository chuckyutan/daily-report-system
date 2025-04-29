FROM php:8.2-apache

# Install PDO MySQL extension and other dependencies
RUN docker-php-ext-install pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application source
COPY . /var/www/html

# Set permissions for storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose ports
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
