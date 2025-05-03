#!/bin/bash
set -e

if [ ! -f artisan ]; then
  echo "🚫 Laravel não está instalado no diretório $PROJECT_DIR"
fi

until nc -z $DB_HOST $DB_PORT; do
  echo "⏳ Aguardando o banco de dados ($DB_HOST:$DB_PORT)..."
  sleep 1
done

echo "✅ Banco disponível! Rodando composer..."
composer install

chown www-data:www-data .env
chmod 644 .env

echo "🔑 Gerando chave da aplicação..."
php artisan key:generate

echo "🧩 Rodando migrations..."
php artisan migrate

echo "🌱 Rodando seeders..."
php artisan db:seed

exec "$@"
