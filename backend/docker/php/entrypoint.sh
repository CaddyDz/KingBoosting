#!/bin/sh
# Production entrypoint: build Laravel's caches from the runtime environment,
# optionally migrate, then run the container command (php-fpm by default; the
# queue, reverb and scheduler services pass their own artisan command).
set -e

if [ "${LARAVEL_OPTIMIZE:-true}" = "true" ]; then
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
	php artisan migrate --force
fi

exec "$@"
