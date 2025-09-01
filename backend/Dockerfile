FROM php:8.2-cli

ARG APP_ENV=prod
ARG APP_DEBUG=0
ENV APP_ENV=${APP_ENV}
ENV APP_DEBUG=${APP_DEBUG}

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev \
    && docker-php-ext-install pdo_mysql intl zip gd bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer install --no-dev --optimize-autoloader --no-scripts \
    && mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

USER www-data

EXPOSE 8000
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]