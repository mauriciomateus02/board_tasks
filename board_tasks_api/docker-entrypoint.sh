#!/bin/sh

set -e

echo "🔄 Executando migrations..."
php artisan migrate --force

echo "🌱 Executando seed..."
php artisan db:seed --force

echo "🚀 Iniciando PHP-FPM..."
exec php-fpm