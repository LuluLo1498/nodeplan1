FROM php:8.2-apache

# 1. Instalar dependencias del sistema y extensiones PHP (MySQL y PostgreSQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# 2. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Configurar Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 4. Copiar proyecto
WORKDIR /var/www/html
COPY . .

# 5. Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# 6. Permisos
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80

# 7. Comando de inicio (Un solo CMD)
# Nota: Quitamos la migración de aquí para que no falle el arranque.
CMD ["apache2-foreground"]