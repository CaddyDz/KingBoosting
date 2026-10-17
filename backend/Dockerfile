# syntax=docker/dockerfile:1

# King Boosting API (Laravel 13, PHP 8.4). Build from this directory:
#
#   docker build -t kingboosting-app backend
#   docker build --target web -t kingboosting-web backend
#
# Targets:
#   app (default)  production php-fpm image; also runs the queue worker, Reverb and the scheduler
#   web            production nginx image: serves public/ and passes PHP requests to `app`
#   dev            development image used by compose.yaml (the code is bind-mounted)

ARG PHP_VERSION=8.4
ARG NGINX_VERSION=1.30

FROM composer:2 AS composer

# -----------------------------------------------------------------------------
# PHP-FPM runtime shared by every PHP target.
# pcntl lets queue:work and reverb:start stop gracefully on SIGTERM.
# -----------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-bookworm AS base

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
	apt-get update; \
	apt-get upgrade -y; \
	apt-get install -y --no-install-recommends libfcgi-bin unzip; \
	install-php-extensions intl opcache pcntl pdo_mysql; \
	rm -rf /var/lib/apt/lists/*

COPY docker/php/php-fpm.d/zz-app.conf /usr/local/etc/php-fpm.d/zz-app.conf

# www-data's home (/var/www) isn't writable; tinker (PsySH) keeps its config here instead.
ENV XDG_CONFIG_HOME=/tmp/.config

WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Production Composer dependencies.
# -----------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 \
	COMPOSER_HOME=/tmp/composer \
	COMPOSER_CACHE_DIR=/tmp/composer/cache

COPY composer.json composer.lock ./

RUN --mount=type=cache,target=/tmp/composer/cache \
	composer install --no-dev --no-scripts --no-autoloader --no-interaction --no-progress --prefer-dist

# -----------------------------------------------------------------------------
# Application build: optimized autoloader and package discovery.
# -----------------------------------------------------------------------------
FROM vendor AS build

COPY . .

RUN set -eux; \
	rm -rf docker; \
	mkdir -p bootstrap/cache storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs; \
	composer dump-autoload --optimize --classmap-authoritative --no-dev --no-interaction; \
	php artisan storage:link

# -----------------------------------------------------------------------------
# Production nginx: serves public/ and passes PHP requests to php-fpm.
# -----------------------------------------------------------------------------
FROM nginx:${NGINX_VERSION}-alpine AS web

ENV NGINX_PORT=8080 \
	NGINX_RESOLVER=127.0.0.11 \
	NGINX_FASTCGI_READ_TIMEOUT=60s \
	PHP_FPM_HOST=app \
	PHP_FPM_PORT=9000

COPY docker/nginx/templates/ /etc/nginx/templates/
COPY --from=build /var/www/html/public /var/www/html/public

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
	CMD wget -q -O /dev/null "http://127.0.0.1:${NGINX_PORT}/nginx-health" || exit 1

# -----------------------------------------------------------------------------
# Development image. Adds Composer, git and Xdebug (off unless XDEBUG_MODE is
# set), and runs as www-data remapped to your host UID/GID so files created in
# the container belong to you.
# -----------------------------------------------------------------------------
FROM base AS dev

ARG UID=1000
ARG GID=1000

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends git; \
	git config --system --add safe.directory /var/www/html; \
	install-php-extensions xdebug; \
	rm -rf /var/lib/apt/lists/*; \
	groupmod --non-unique --gid "$GID" www-data; \
	usermod --non-unique --uid "$UID" --gid "$GID" www-data; \
	mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d/development.ini $PHP_INI_DIR/conf.d/zz-app.ini
COPY --chmod=0755 docker/php/entrypoint-dev.sh /usr/local/bin/docker-entrypoint

ENV COMPOSER_HOME=/tmp/composer \
	XDEBUG_MODE=off

USER www-data

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# -----------------------------------------------------------------------------
# Production php-fpm image (default target).
# -----------------------------------------------------------------------------
FROM base AS app

ENV APP_ENV=production \
	APP_DEBUG=false \
	LOG_CHANNEL=stderr

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/conf.d/production.ini $PHP_INI_DIR/conf.d/zz-app.ini
COPY --chmod=0755 docker/php/entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --from=build /var/www/html /var/www/html

# Code stays root-owned (read-only for php-fpm); only runtime state is writable.
# The php image ships /var/www/html as www-data 1777, so reset the root itself too.
RUN set -eux; \
	chown root:root /var/www/html; \
	chmod 755 /var/www/html; \
	chown -R www-data:www-data storage bootstrap/cache

USER www-data

HEALTHCHECK --interval=30s --timeout=5s --start-period=60s --retries=3 \
	CMD SCRIPT_NAME=/fpm-ping SCRIPT_FILENAME=/fpm-ping REQUEST_METHOD=GET cgi-fcgi -bind -connect 127.0.0.1:9000 | grep -q pong

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]
