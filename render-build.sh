#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader

# Optimizar Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones (opcional, pero recomendado)
# php artisan migrate --force