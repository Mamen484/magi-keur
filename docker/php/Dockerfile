FROM php:8.2.0-cli

RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini

RUN apt-get update && apt-get install -y \
    git unzip zip curl libpq-dev libicu-dev \
    && docker-php-ext-install intl pdo pdo_pgsql opcache

# Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
