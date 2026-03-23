FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Desactivar MPM extra y activar mod_rewrite
RUN a2dismod mpm_prefork mpm_worker || true
RUN a2enmod rewrite

# Copiar proyecto
COPY . /var/www/html/

# Dar permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Apache DocumentRoot en /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Exponer puerto 80
EXPOSE 80

# Iniciar Apache
CMD ["apache2-foreground"]