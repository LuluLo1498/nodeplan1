FROM php:8.2-apache

# 1. Instalar dependencias del sistema y librerías necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
    dos2unix

# 2. Instalar extensiones de PHP (incluyendo Postgres y MySQL)
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# 3. Instalar Node.js (Necesario para compilar Vite/CSS/JS)
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# 4. Configurar Apache para que apunte a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 5. Preparar el directorio de trabajo
WORKDIR /var/www/html
COPY . .

# 6. Instalar Composer (dependencias de PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Instalar dependencias de NPM y COMPILAR el Frontend (Vite)
# Esto arreglará que la página "se vea mal"
RUN npm install
RUN npm run build

# 8. Crear carpetas y dar permisos de escritura a Laravel
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 9. Limpiar formato del entrypoint y dar permisos de ejecución
RUN dos2unix entrypoint.sh && chmod +x entrypoint.sh

# ... (todo tu Dockerfile anterior hasta la línea de EXPOSE 80) ...

EXPOSE 80

# Forzamos la ejecución manual del comando antes de Apache
ENTRYPOINT ["/bin/sh", "-c", "php artisan migrate --force && apache2-foreground"]