# --- Laravel PHP-FPM Backend ---
FROM php:8.2-fpm-alpine AS backend

WORKDIR /var/www

# Install system dependencies
RUN apk add --no-cache git curl mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for Laravel storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Ensure storage link does not cause errors
RUN rm -rf /var/www/public/storage && ln -s /var/www/storage /var/www/public/storage

# Expose PHP-FPM port
EXPOSE 9000

# --- Laravel Mix Frontend ---
FROM node:latest AS frontend

WORKDIR /app

# Copy frontend dependencies
COPY package.json package-lock.json ./
RUN npm install

# Copy frontend files and build assets
COPY . .
RUN npx mix --production

# --- Final Deployment Stage ---
FROM backend AS final

WORKDIR /var/www

# Copy compiled frontend assets
COPY --from=frontend /app/public /var/www/public

# Expose application port
EXPOSE 9000

# Ensure PHP-FPM stays running persistently
CMD ["sh", "-c", "php-fpm -D && sleep 10 && php artisan migrate --force && rm -rf /var/www/public/storage && php artisan storage:link && exec php-fpm"]
