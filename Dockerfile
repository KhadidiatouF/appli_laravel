# Étape 1: Build des dépendances PHP
FROM composer:2.6 AS composer-build

WORKDIR /app

# Copier les fichiers de dépendances
COPY composer.json composer.lock ./

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Étape 2: Image finale pour l'application
FROM php:8.3-fpm-alpine

# Installer les extensions PHP nécessaires
RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Créer un utilisateur non-root
RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les dépendances installées depuis l'étape de build
COPY --from=composer-build /app/vendor ./vendor

# Copier le reste du code de l'application
COPY . .

# Créer les répertoires nécessaires et définir les permissions
RUN mkdir -p storage/framework/{cache,data,sessions,testing,views} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Créer un fichier .env minimal pour le build
RUN echo "APP_NAME=Laravel" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_KEY=base64:W0DU3eWug8uFCmcOATnFBGEJbgfoFK2beqVTH6qRwrg=" >> .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_URL=https://appli-laravel-groupe.onrender.com" >> .env && \
    echo "" >> .env && \
    echo "LOG_CHANNEL=stack" >> .env && \
    echo "LOG_LEVEL=error" >> .env && \
    echo "" >> .env && \
    echo "DB_CONNECTION=pgsql" >> .env && \
    echo "DB_HOST=dpg-d44aobodl3ps73aplrm0-a.oregon-postgres.render.com" >> .env && \
    echo "DB_PORT=5432" >> .env && \
    echo "DB_DATABASE=gestion_comptes_api" >> .env && \
    echo "DB_USERNAME=gestion_comptes_api_user" >> .env && \
    echo "DB_PASSWORD=E3EM3gYPCojhxraLPA98sqGvR1bVSoNu" >> .env && \
    echo "" >> .env && \
    echo "CACHE_DRIVER=file" >> .env && \
    echo "SESSION_DRIVER=file" >> .env && \
    echo "QUEUE_CONNECTION=sync" >> .env && \
    echo "" >> .env && \
    echo "PASSPORT_PERSONAL_ACCESS_CLIENT_ID=1" >> .env && \
    echo "PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET=zlxItbTiDBOsWMwESt70QZQwsLE3iSUn54raQ0QD" >> .env && \
    echo "" >> .env && \
    echo "PASSPORT_PRIVATE_KEY=" >> .env && \
    echo "PASSPORT_PUBLIC_KEY=" >> .env

RUN chown laravel:laravel .env

# Générer la clé d'application et optimiser
USER laravel
# RUN php artisan key:generate --force \
  RUN php artisan config:cache \
    # && php artisan route:cache \
    && php artisan view:cache
USER root

# Copier le script d'entrée (optionnel)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exposer le port 8000
EXPOSE 8000

# Commande par défaut
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]