# ────────────── Dockerfile para proyecto MVC PHP ──────────────

# 1️⃣ Base image PHP 8.2 con Apache
FROM php:8.2-apache

# 2️⃣ Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# 3️⃣ Activar mod_rewrite para rutas amigables
RUN a2enmod rewrite

# 4️⃣ Copiar todo el proyecto al contenedor
COPY . /var/www/html/

# 5️⃣ Dar permisos correctos a todo el proyecto
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 6️⃣ Establecer directorio de trabajo
WORKDIR /var/www/html

# 7️⃣ Configurar Apache para usar public como DocumentRoot
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 8️⃣ Exponer puerto 80
EXPOSE 80

# 9️⃣ Iniciar Apache en foreground
CMD ["apache2-foreground"]