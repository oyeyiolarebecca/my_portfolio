FROM composer:2 AS composer_deps
WORKDIR /app

COPY composer.json composer.lock ./
# Avoid running Composer scripts here because they require the full app (e.g. `artisan`).
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

FROM node:20-alpine AS frontend_build
WORKDIR /app

COPY package.json package-lock.json vite.config.js ./
COPY resources ./resources
RUN npm ci
RUN npm run build

FROM php:8.3-cli-alpine AS app
WORKDIR /app

RUN apk add --no-cache \
    bash \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    postgresql-dev \
    sqlite-dev \
    unzip \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_pgsql \
    pdo_sqlite \
    zip

COPY . .
COPY --from=composer_deps /app/vendor ./vendor
COPY --from=frontend_build /app/public/build ./public/build

RUN mkdir -p storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

EXPOSE 10000

CMD ["sh", "docker/start.sh"]
