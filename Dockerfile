FROM php:8.2-fpm

# Instala dependências do sistema e extensões do PHP
RUN apt update && apt install -y \
    unzip \
    curl \
    netcat-openbsd \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
 && docker-php-ext-install pdo pdo_mysql \
 && apt clean && rm -rf /var/lib/apt/lists/*

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o script de entrada
COPY scripts/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
