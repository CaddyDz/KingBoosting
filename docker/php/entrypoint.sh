#!/bin/sh
# Production entrypoint: build Laravel's caches from the runtime environment,
# optionally migrate, then run the container command (php-fpm by default).
set -e

if [ "${LARAVEL_OPTIMIZE:-true}" = "true" ]; then
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
fi

# Off by default: generation currently fails because the annotations in docs/
# aren't attached to classes, which swagger-php 4 requires. docs/info.php points
# the docs at APP_URL, so once that's fixed they're generated per environment here.
if [ "${GENERATE_API_DOCS:-false}" = "true" ]; then
	php artisan l5-swagger:generate || echo "entrypoint: API docs generation failed, continuing" >&2
fi

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
	php artisan migrate --force
fi

exec "$@"
