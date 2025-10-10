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
    
    # Ejecutar migraciones de Central si es necesario
    echo "Verificando migraciones de Central..."
    cd /app/code
    php artisan migrate --force
    echo "Migraciones de Central verificadas"
    
    # Generar claves de Passport si no existen
    if [ ! -f "/app/code/storage/oauth-private.key" ]; then
        echo "Generando claves de cifrado de Passport..."
        cd /app/code
        php artisan passport:keys --force
        echo "Claves de Passport generadas exitosamente"
    else
        echo "Claves de Passport ya existen"
    fi
    
    # Ejecutar seeders de Central
    echo "Ejecutando seeders de Central..."
    cd /app/code
    php artisan db:seed --class=Database\\Seeders\\Central\\CentralDBSeeder --force
    echo "Seeders de Central ejecutados"
    
    # Crear tenants por defecto si no existen
    echo "Verificando tenants por defecto..."
    cd /app/code
    php artisan tenants:create 2>/dev/null || echo "Tenants ya existen o error al crearlos"
    
    # Ejecutar seeders de Tenants
    echo "Ejecutando seeders de Tenants..."
    cd /app/code
    php artisan tenants:seed --class=Database\\Seeders\\Tenant\\TenantDBSeeder --force 2>/dev/null || echo "Seeders de Tenants ya ejecutados"
    
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