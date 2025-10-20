# Use an official PHP runtime as a parent image, supporting multi-arch
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libssl-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) zip \
    && a2enmod rewrite \
    && a2enmod headers

# Copy the application files
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Ensure correct permissions for web server
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80 for Apache
EXPOSE 80

# Command to run Apache in the foreground
CMD ["apache2-foreground"]