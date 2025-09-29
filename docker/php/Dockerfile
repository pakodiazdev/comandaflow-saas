FROM php:8.0-fpm

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
WORKDIR /workspace

# Cambiar propietario del directorio
RUN chown -R www-data:www-data /workspace

# Cambiar al usuario www-data
USER www-data

# Exponer puerto 9000
EXPOSE 9000

CMD ["php-fpm"]