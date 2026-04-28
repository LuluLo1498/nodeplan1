FROM php:8.2-apache

# 1. Instalar dependencias y extensiones (MySQL y Postgres)
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libpq-dev \
    zip unzip git curl dos2unix

RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# 2. Configurar Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 3. Preparar código
WORKDIR /var/www/html
COPY . .
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 4. Permisos
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 5. Configurar el script de entrada (IMPORTANTE)
RUN dos2unix entrypoint.sh && chmod +x entrypoint.sh

EXPOSE 80

# Usamos ENTRYPOINT para asegurar que las migraciones corran siempre
ENTRYPOINT ["/var/www/html/entrypoint.sh"]