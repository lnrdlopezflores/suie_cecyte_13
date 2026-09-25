FROM php:8.3-cli-alpine

# 1. Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apk add --no-cache \
    bash \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    mysql-client \
    postgresql-dev \
    nodejs \
    npm

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring zip bcmath gd

# 2. Instalar Composer oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Establecer directorio de trabajo
WORKDIR /var/www/html

# 4. Copiar archivos del proyecto
COPY . .

# 5. Instalar dependencias de PHP y compilar assets si usas Vite/Tailwind
RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN if [ -f "package.json" ]; then npm install && npm run build; fi

# 6. Permisos de carpetas de almacenamiento y caché
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 7. Exponer el puerto asignado por Render
EXPOSE 10000

# 8. Script de arranque: limpia/crea cachés, genera enlace simbólico y levanta el servidor
CMD php artisan storage:link --force || true && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}