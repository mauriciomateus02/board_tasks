FROM php:8.2-fpm-alpine

# Instala dependências do sistema (nomes corretos para Alpine)
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    postgresql-dev

# Instala extensões PHP
RUN docker-php-ext-install \
    pdo pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Copia o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copia os arquivos da aplicação (sem --chown por enquanto)
COPY . /var/www

# Instala as dependências do Composer
# RUN composer install --no-dev --optimize-autoloader --no-interaction

# Ajusta permissões: diretórios storage e bootstrap/cache precisam ser graváveis
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage /var/www/bootstrap/cache

# Muda para o usuário www-data (recomendado para segurança)
USER www-data

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]