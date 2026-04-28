#!/bin/sh

# Si la variable existe, borra todo y recrea (solo para arreglar el error actual)
if [ "$MIGRATE_FRESH" = "true" ]; then
    echo "Limpiando base de datos y recreando tablas..."
    php artisan migrate:fresh --force
else
    php artisan migrate --force
fi

# Iniciar Apache
exec apache2-foreground