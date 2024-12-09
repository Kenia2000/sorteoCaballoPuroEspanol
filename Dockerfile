# Usa una imagen base de PHP con Apache
FROM php:8.1-apache

# Instalar Composer
RUN apt-get update && apt-get install -y \
    unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiar el contenido del proyecto al contenedor
COPY . /var/www/html

# Establecer permisos para la carpeta de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando para iniciar Apache al arrancar el contenedor
CMD ["apache2-foreground"]
