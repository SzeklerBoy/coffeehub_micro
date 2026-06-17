# --- 1. LÉPÉS: PHP Függőségek letöltése (Composer) ---
FROM composer:latest AS vendor
WORKDIR /app
COPY . .
# Letöltjük a csomagokat (létrejön a vendor mappa)
RUN composer install --optimize-autoloader --ignore-platform-reqs

# --- 2. LÉPÉS: Frontend assetek építése (Node.js) ---
FROM node:18-alpine AS frontend
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
# FONTOS: Áthozzuk a vendor mappát az 1. lépésből, hogy a Ziggy működjön!
COPY --from=vendor /app/vendor ./vendor
RUN npm run build

# --- 3. LÉPÉS: Végső PHP Backend beállítása ---
FROM php:8.2-cli
WORKDIR /var/www

# Rendszerkönyvtárak telepítése
RUN apt-get update && apt-get install -y libzip-dev zip unzip git \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY . .
# Áthozzuk a kész vendor mappát és a lefordított frontendet
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN rm -rf bootstrap/cache/*.php

EXPOSE 8000

# Laravel indítása
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]