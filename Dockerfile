FROM php:8.2-apache

# Instalar extensiones necesarias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar los archivos del proyecto al contenedor
COPY ./www /var/www/html

# Configurar permisos para el directorio
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Exponer el puerto 80 para el servidor web
EXPOSE 80
