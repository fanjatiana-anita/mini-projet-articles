FROM php:8.3-apache

# Extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
        libpq-dev \
        libonig-dev \
        libzip-dev \
        unzip \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        pgsql \
        mbstring \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Activer mod_rewrite + headers + expires (Lighthouse)
RUN a2enmod rewrite headers expires deflate

# Autoriser .htaccess dans tout le site
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Copier tout le code source
COPY . /var/www/html/

# En Docker, le site est à la racine → utiliser le .htaccess adapté
RUN cp /var/www/html/.htaccess_docker /var/www/html/.htaccess

# Permissions uploads
RUN mkdir -p /var/www/html/public/images \
    && mkdir -p /var/www/html/public/uploads \
    && chown -R www-data:www-data /var/www/html/public \
    && chmod -R 755 /var/www/html/public

WORKDIR /var/www/html

EXPOSE 80
