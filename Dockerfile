# Render.com Production Dockerfile for PHP 8.2 + Apache + MySQL PDO
FROM php:8.2-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite and mod_headers for security headers
RUN a2enmod rewrite headers

# Copy application files to Apache root
COPY . /var/www/html/

# Ensure web server owns files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose Render standard port
EXPOSE 80 10000

# Configure Apache to listen on $PORT provided by Render (default 80 if not set)
CMD sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && apache2-foreground
