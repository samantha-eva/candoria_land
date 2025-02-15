FROM php:8.2-fpm

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libzip-dev default-mysql-client

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql zip gd opcache

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le code de l'application dans le conteneur
COPY . /var/www

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier la configuration PHP (opcache)
COPY opcache.ini /usr/local/etc/php/conf.d/opcache.ini


CMD ["php-fpm"]
