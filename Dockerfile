# syntax=docker/dockerfile:1

# King Boosting API and Nova admin panel (Laravel 8, PHP 8.0).
#
# Targets:
#   app (default)  production php-fpm image
#   web            production nginx image: serves public/ and passes PHP requests to `app`
#   dev            development image used by compose.yaml (the code is bind-mounted)
#
# The production targets install Laravel Nova, so they need your nova.laravel.com
# credentials. Pass them as a BuildKit secret (an auth.json file, see README) so they
# never end up in an image layer:
#
#   docker build --secret id=composer_auth,src=auth.json -t kingboosting-app .
#   docker build --secret id=composer_auth,src=auth.json --target web -t kingboosting-web .

ARG PHP_VERSION=8.0
ARG NGINX_VERSION=1.30

FROM composer:2 AS composer

# -----------------------------------------------------------------------------
# PHP-FPM runtime shared by every PHP target.
# PHP 8.0 images were never published for Debian bookworm, so this is bullseye.
# Debian has moved bullseye, including its final security updates, to
# archive.debian.org, so packages come from there.
# -----------------------------------------------------------------------------
FROM php:${PHP_VERSION}-fpm-bullseye AS base

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN set -eux; \
	sed -i 's|http://deb.debian.org|http://archive.debian.org|g' /etc/apt/sources.list; \
	echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99archive; \
	apt-get update; \
	apt-get upgrade -y; \
	apt-get install -y --no-install-recommends libfcgi-bin unzip; \
	install-php-extensions intl opcache pdo_mysql; \
	rm -rf /var/lib/apt/lists/*

COPY docker/php/php-fpm.d/zz-app.conf /usr/local/etc/php-fpm.d/zz-app.conf

# www-data's home (/var/www) isn't writable; tinker (PsySH) keeps its config here instead.
ENV XDG_CONFIG_HOME=/tmp/.config

WORKDIR /var/www/html

# -----------------------------------------------------------------------------
# Production Composer dependencies. Nova is downloaded from nova.laravel.com
# (needs the composer_auth secret); NovaChat is a path repository, so it's copied
# in first and mirrored into vendor/ instead of symlinked.
# -----------------------------------------------------------------------------
FROM base AS vendor

COPY --from=composer /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1 \
	COMPOSER_HOME=/tmp/composer \
	COMPOSER_CACHE_DIR=/tmp/composer/cache \
	COMPOSER_MIRROR_PATH_REPOS=1

COPY composer.json composer.lock ./
COPY nova-components/ nova-components/

RUN --mount=type=secret,id=composer_auth,target=/tmp/composer/auth.json \
	--mount=type=cache,target=/tmp/composer/cache \
	composer install --no-dev --no-scripts --no-autoloader --no-interaction --no-progress --prefer-dist

# -----------------------------------------------------------------------------
# Application build: optimized autoloader, package discovery, and Nova's assets
# published from the installed version (public/vendor/nova is gitignored).
# -----------------------------------------------------------------------------
FROM vendor AS build

COPY . .

RUN set -eux; \
	rm -rf docker; \
	mkdir -p bootstrap/cache storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs; \
	composer dump-autoload --optimize --no-dev --no-interaction; \
	php artisan vendor:publish --tag=nova-assets --force; \
	php artisan storage:link

# -----------------------------------------------------------------------------
# Production nginx: serves public/ and passes PHP requests to php-fpm.
# Mount the app's storage volume at /var/www/html/storage so /storage/* works.
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
