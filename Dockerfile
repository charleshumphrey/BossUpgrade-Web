# Use official PHP Apache image
FROM php:8.2-apache

# Set environment variables for timezone
ENV TZ=UTC

# Install system dependencies, timezone info, and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libonig-dev \
    libzip-dev \
    zip \
    sqlite3 \
    libsqlite3-dev \
    tzdata \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip bcmath

# Set timezone inside container
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer from Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy all project files into container
COPY . .

# Set Apache to use Laravel public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

# Ensure Laravel directories and Firebase credentials are accessible
RUN mkdir -p /var/www/storage/serviceaccountkey \
    && touch /var/www/database/database.sqlite \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Install PHP dependencies without dev packages
RUN composer install --no-dev --optimize-autoloader

# Expose web server port
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
