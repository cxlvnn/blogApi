# syntax=docker/dockerfile:1

# First stage: Node build (if resources exist)
FROM node:22 AS node_builder
WORKDIR /app
COPY package.json package-lock.json* ./
RUN ([ -f package-lock.json ] && npm ci) || (npm install --ignore-scripts 2>/dev/null || true)
COPY . .
RUN if [ -d resources ]; then npm run build; else mkdir -p public/build; fi

# Second stage: PHP runtime
FROM php:8.4-cli-bookworm

LABEL maintainer="https://github.com/cxlnv"
LABEL description="BlogApi - A RESTful Laravel Blog API"

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        ca-certificates \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        sqlite3 \
        libsqlite3-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_sqlite \
        bcmath \
        opcache \
        pcntl \
        mbstring \
        exif \
        xml \
        zip \
        gd \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/tmp/composer

WORKDIR /var/www

# Copy dependency files first for layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --no-progress --optimize-autoloader --no-interaction --no-scripts

# Copy application code
COPY . .
RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
COPY --from=node_builder /app/public/build public/build

# Remove .env so the container uses only real environment variables (best practice for Docker)
RUN rm -f /var/www/.env

# Copy entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set permissions for Laravel
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions storage/logs \
    && chmod -R 775 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true

# SQLite default path prep
RUN touch database/database.sqlite && chmod 664 database/database.sqlite

# Default env overrides for Docker
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/database/database.sqlite
ENV CACHE_STORE=file
ENV SESSION_DRIVER=file
ENV QUEUE_CONNECTION=sync
ENV BROADCAST_CONNECTION=log
ENV LOG_CHANNEL=stderr
ENV LOG_LEVEL=warning
ENV APP_URL=http://localhost:8000

EXPOSE 8000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
