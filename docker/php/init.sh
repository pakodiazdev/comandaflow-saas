#!/bin/bash

# Script de inicialización para optimizar Laravel en Docker

# Esperar un momento para que los volúmenes estén montados
sleep 2

# Verificar si Laravel está instalado
if [ -f "/app/code/artisan" ]; then
    echo "Laravel detectado, configurando volúmenes optimizados..."
    
    # Crear subdirectorios en storage/framework si no existen
    mkdir -p /app/code/storage/framework/sessions
    mkdir -p /app/code/storage/framework/views  
    mkdir -p /app/code/storage/framework/cache
    mkdir -p /app/code/storage/logs
    
    # Asegurar permisos correctos para directorios de cache y logs
    chmod -R 775 /app/code/storage/framework /app/code/storage/logs /app/code/bootstrap/cache 2>/dev/null || true
    
    # Si vendor está vacío, instalar dependencias
    if [ ! -d "/app/code/vendor/laravel" ]; then
        echo "Instalando dependencias de Composer..."
        cd /app/code
        composer install --optimize-autoloader --no-dev
    fi
    
    # Optimizar Laravel para producción si no es debug
    if [ "${APP_DEBUG}" != "true" ]; then
        cd /app/code
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
    fi
    
    echo "Inicialización completada - vendor, framework cache y logs en volúmenes optimizados"
fi

# Iniciar PHP-FPM
exec php-fpm