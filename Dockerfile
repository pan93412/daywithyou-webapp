FROM dunglas/frankenphp:latest

WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    netcat-openbsd \
    && npm install -g pnpm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    pcntl \
    pdo_mysql \
    redis \
    mbstring \
    exif \
    bcmath \
    gd \
    opcache \
    zip

# Set recommended PHP.ini settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application files
COPY . .

# Generate optimized autoloader and clear cache
RUN composer dump-autoload --optimize \
    && php artisan package:discover \
    && php artisan optimize:clear \
    && php artisan optimize

# Set file permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app/storage

# Install and build frontend assets
RUN pnpm install --frozen-lockfile \
    && pnpm run build \
    && rm -rf node_modules

# Make entrypoint script executable
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80
EXPOSE 80

# Set the entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
