FROM php:8.2

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    && docker-php-ext-install zip pdo pdo_mysql intl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-progress --prefer-dist

COPY . .

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN chown -R www-data:www-data /var/www/html/ || true

EXPOSE 9000

CMD ["php", "-S", "0.0.0.0:9000"]