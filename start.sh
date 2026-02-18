#!/bin/bash
set -e

# Generar .env desde variables de entorno
cat > /app/.env <<EOF
APP_NAME=${APP_NAME:-MecanicCRM}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
EOF

# Permisos
chmod -R 775 /app/storage /app/bootstrap/cache

# Limpiar config
php artisan config:clear
php artisan cache:clear

# Ejecutar migraciones y seeder del admin
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder --force 2>/dev/null || true

# Iniciar servidor
exec php -S 0.0.0.0:8080 -t /app/public_html /app/public_html/router.php
