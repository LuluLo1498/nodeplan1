#!/bin/sh

# Crear el enlace simbólico para las imágenes (si usas storage)
php artisan storage:link --force

# Ejecutar las migraciones de la base de datos
# El flag --force es obligatorio en producción
php artisan migrate --force

# Iniciar Apache en primer plano para que Render no cierre el contenedor
echo "Arrancando servidor Apache..."
exec apache2-foreground