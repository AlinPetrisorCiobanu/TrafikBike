# Usamos PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activar mod_rewrite para que las rutas "amigables" funcionen
RUN a2enmod rewrite

# Copiar el proyecto al contenedor
COPY . /var/www/html/

# Definir la carpeta pública como raíz del documento
WORKDIR /var/www/html/public

# Actualizar la configuración de Apache para apuntar a la carpeta public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Dar permisos correctos (opcional pero recomendado)
RUN chown -R www-data:www-data /var/www/html/public \
    && chmod -R 755 /var/www/html/public

# Exponer puerto 80
EXPOSE 80