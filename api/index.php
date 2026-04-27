<?php

// 1. Forzar la visualización de errores (Modo Debug extremo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Configurar carpetas temporales (Vercel no deja escribir en las normales)
// Esto evita errores de "Permission Denied"
putenv('APP_CONFIG_CACHE=/tmp/config.php');
putenv('APP_ROUTES_CACHE=/tmp/routes.php');
putenv('APP_SERVICES_CACHE=/tmp/services.php');
putenv('APP_PACKAGES_CACHE=/tmp/packages.php');

// 3. Cargar Laravel
require __DIR__ . '/../public/index.php';