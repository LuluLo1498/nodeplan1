#!/bin/sh

# Ejecutar migraciones (esto creará las tablas que Laravel no encontraba)
php artisan migrate --force

# Iniciar Apache en primer plano
exec apache2-foreground