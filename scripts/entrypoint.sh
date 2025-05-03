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

if [ ! -f .env ]; then
    echo "Arquivo .env não encontrado. Copiando .env.example para .env..."
    cp .env.example .env
fi

echo "🔑 Gerando chave da aplicação..."
php artisan key:generate

chown www-data:www-data .env
chmod 644 .env

echo "🧩 Rodando migrations..."
php artisan migrate

echo "🌱 Rodando seeders..."
php artisan db:seed

exec "$@"
