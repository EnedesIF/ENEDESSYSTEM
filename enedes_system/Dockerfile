FROM php:8.1-apache

# Instalar dependências do sistema primeiro (para cache do Docker)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Copiar arquivos da aplicação
COPY . /var/www/html/

# Definir permissões corretas
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expor porta
EXPOSE 80

# Comando de inicialização
CMD ["apache2-foreground"]
