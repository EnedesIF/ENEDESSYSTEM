FROM php:8.1-apache

# Habilitar mod_rewrite (caso use .htaccess futuramente)
RUN a2enmod rewrite

# Copia todos os arquivos da pasta local para o Apache
COPY . /var/www/html/

# Dar permissões (boa prática)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
