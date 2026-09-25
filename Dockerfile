FROM php:8.3-cli-alpine

# Dependencias del sistema y extensiones de PHP
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    mariadb-connector-c-dev \
    postgresql-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip bcmath gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar proyecto
COPY . .

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Compilar assets si existe package.json
RUN if [ -f "package.json" ]; then npm install && npm run build; fi

# Permisos de carpetas de escritura
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x docker-entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]