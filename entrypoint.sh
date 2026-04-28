#!/bin/sh

# Limpiar cachés de Laravel
php artisan config:clear
php artisan route:clear

# Ejecutar las migraciones (esto creará las tablas de notas y asignaturas)
php artisan migrate --force

# Iniciar el servidor
exec apache2-foreground