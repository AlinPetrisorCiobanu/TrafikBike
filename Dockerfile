# Imagen base PHP con Apache
FROM php:8.2-apache

# Instalar extensiones para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activar mod_rewrite
RUN a2enmod rewrite

# Copiar todo el proyecto
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Directorio de trabajo
WORKDIR /var/www/html

# DocumentRoot apuntando a /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]