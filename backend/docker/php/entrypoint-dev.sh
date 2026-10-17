#!/bin/sh
# Development entrypoint: make a fresh clone bootable and keep vendor/ in sync
# with composer.lock, then run the container command (php-fpm by default).
# Only the long-running php-fpm container does the setup, so one-off
# `docker compose run app ...` commands never race it on the shared vendor/.
set -e

if [ "$1" = "php-fpm" ]; then
	if [ ! -f .env ]; then
		echo "entrypoint: no .env found, copying .env.example"
		cp .env.example .env
	fi

	lock_hash=$(sha1sum composer.lock | cut -d' ' -f1)
	stamp=vendor/.composer-lock.sha1

	if [ ! -f vendor/autoload.php ]; then
		echo "entrypoint: vendor/ is missing, running composer install"
		composer install --no-interaction --no-progress
		echo "$lock_hash" > "$stamp"
	elif [ "$(cat "$stamp" 2>/dev/null)" != "$lock_hash" ]; then
		echo "entrypoint: composer.lock changed, running composer install"
		if composer install --no-interaction --no-progress; then
			echo "$lock_hash" > "$stamp"
		else
			echo "entrypoint: composer install failed, starting with the existing vendor/" >&2
		fi
	fi

	if ! grep -Eq '^APP_KEY=.+' .env; then
		php artisan key:generate --ansi
	fi
fi

exec "$@"
