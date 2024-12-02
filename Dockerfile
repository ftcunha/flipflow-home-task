# Use the official PHP image as the base image
FROM php:8.3-cli

# Set the working directory
WORKDIR /app

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the existing application directory contents
COPY . /app

# Copy the existing application directory permissions
COPY --chown=www-data:www-data . /app

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 8000 and start the server
EXPOSE 8000

CMD php artisan serve