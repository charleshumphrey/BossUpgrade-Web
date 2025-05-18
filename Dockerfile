# Use official PHP-Apache base image
FROM php:8.2-apache

# Install system dependencies and PHP extensions
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

# Set working directory
WORKDIR /var/www

# Copy Laravel app files
COPY . .

# Set correct Apache DocumentRoot to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/public

# Update Apache config to point to /public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Fix permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Generate Laravel app key if not set via env (optional fallback)
# RUN php artisan key:generate

# Run migrations (optional; use with caution if DB migrations needed)
# RUN php artisan migrate --force

# Expose HTTP port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
