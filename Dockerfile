FROM php:8.1-apache

# Instala suporte a PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copia todos os arquivos da aplicação
COPY . /var/www/html/

# Define permissões corretas
RUN chown -R www-data:www-data /var/www/html

# Ativa mod_rewrite do Apache se precisar de rotas amigáveis
RUN a2enmod rewrite

EXPOSE 80
CMD ["apache2-foreground"]
