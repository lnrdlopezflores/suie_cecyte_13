#!/bin/sh
set -e

# Crear enlace simbólico de storage
php artisan storage:link --force || true

# Limpiar cachés previas
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Iniciar el servidor embebido en el puerto provisto por Render
echo "Iniciando Laravel en el puerto ${PORT:-10000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"