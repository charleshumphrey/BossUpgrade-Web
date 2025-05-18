# Use the official PHP-Apache image
FROM php:8.2-apache

# Install system packages and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    zip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip bcmath

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside container
WORKDIR /var/www

# Copy entire Laravel app into container
COPY . .

# Set Laravel's public folder as Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/public

# Update Apache configuration to serve from /public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Fix permissions for Laravel directories and Firebase credentials
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Install Laravel dependencies (no dev dependencies for production)
RUN composer install --no-dev --optimize-autoloader

# Ensure the SQLite file exists for sessions
RUN touch /var/www/database/database.sqlite \
    && chown www-data:www-data /var/www/database/database.sqlite

# Expose port 80 for Apache
EXPOSE 80

# Start Apache when container launches
CMD ["apache2-foreground"]
