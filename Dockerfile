FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
      bash \
      git \
      curl \
      libzip-dev \
      zip \
      unzip \
      oniguruma-dev \
      postgresql-dev \
  && docker-php-ext-install \
      pdo_pgsql \
      mbstring \
      zip \
      bcmath


COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN adduser -D -u 1000 laravel \
  && chown -R laravel:laravel /var/www

USER laravel

CMD ["php-fpm"]
