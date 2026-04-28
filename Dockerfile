FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensiones de PHP necesarias para Laravel
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para que apunte a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# Copiar el proyecto al contenedor
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel (PHP)
RUN composer install --no-dev --optimize-autoloader

# --- EL CAMBIO ESTÁ AQUÍ ---
# Creamos las carpetas necesarias por si no existen y asignamos permisos
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]
# Asegúrate de que estamos en el directorio correcto
WORKDIR /var/www/html

# Copiar un script de arranque o ejecutar el comando directamente
# Usaremos una cadena de comandos para el inicio
CMD php artisan migrate --force && apache2-foreground