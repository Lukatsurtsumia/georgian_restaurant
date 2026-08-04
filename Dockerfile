# Pain et Pinot — production image (Laravel 13, nginx + php-fpm on :80)
#
# Front-end assets (Vite + Tailwind) are compiled locally and committed under
# public/build, so the production server never runs Node/Vite. This keeps the
# build lightweight and reliable on a shared box (no build-time OOM), and makes
# deploys fast. To update assets: `npm run build` locally, then commit + push.

FROM webdevops/php-nginx:8.4-alpine

ENV WEB_DOCUMENT_ROOT=/app/public \
    COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /app

# Application source (includes the pre-built public/build)
COPY --chown=application:application . /app

# Long-cache static assets (browsers download videos/images/build once, not every visit)
COPY docker/nginx/cache.conf /opt/docker/etc/nginx/vhost.common.d/50-cache.conf

# PHP dependencies (production only) + writable Laravel dirs + public storage link
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
 && ln -sf /app/storage/app/public /app/public/storage \
 && chown -R application:application /app/storage /app/bootstrap/cache
