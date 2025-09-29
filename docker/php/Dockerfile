FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el usuario www-data
RUN groupmod -o -g 1000 www-data && usermod -o -u 1000 -g www-data www-data

# Configurar directorio de trabajo
WORKDIR /app

# Crear directorios para volúmenes con permisos correctos
RUN mkdir -p /app/code/vendor \
             /app/code/storage/framework/sessions \
             /app/code/storage/framework/views \
             /app/code/storage/framework/cache \
             /app/code/storage/logs \
             /app/code/bootstrap/cache \
    && chown -R www-data:www-data /app \
    && chmod -R 775 /app/code/storage /app/code/bootstrap/cache

# Crear script de inicialización
COPY --chown=www-data:www-data docker/php/init.sh /usr/local/bin/init.sh
RUN chmod +x /usr/local/bin/init.sh

# Cambiar al usuario www-data
USER www-data

# Exponer puerto 9000
EXPOSE 9000

# Usar script de inicialización
CMD ["/usr/local/bin/init.sh"]