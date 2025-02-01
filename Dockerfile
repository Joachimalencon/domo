FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install \
    sockets\
    intl \
    pdo \
    pdo_pgsql \
    opcache \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-interaction --optimize-autoloader

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:6969", "-t", "public"]
