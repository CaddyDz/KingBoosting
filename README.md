# King Boosting

### Elo gaming boosting site

[![Check & fix styling](https://github.com/CaddyDz/KingBoosting/actions/workflows/style.yml/badge.svg)](https://github.com/CaddyDz/KingBoosting/actions/workflows/style.yml)
[![Tests](https://github.com/CaddyDz/KingBoosting/actions/workflows/test.yml/badge.svg)](https://github.com/CaddyDz/KingBoosting/actions/workflows/test.yml)

# Installation instructions

## Pre-requisites

-   Node version: 14.x
-   NPM version: 6.x
-   Composer version: 2.x
-   PHP version: 8.0
-   Laravel Nova license: ~3.0
-   MySQL version: ^8.0

### Clone the repository locally

```shell
git clone https://github.com/CaddyDz/KingBoosting.git
```

### Authenticate Nova

```shell
composer config http-basic.nova.laravel.com <username> <password>
```

### Install PHP dependencies

```shell
composer install
```

### Create a .env file

```shell
cp .env.example .env
```

> Create a MySQL database locally and fill in the database credentials in the .env file

# Docker

Runs everything (PHP 8.0, Node, MySQL, Mailpit) in containers; nothing but Docker needs to be installed.

## Local development

```shell
cp .env.example .env   # skip if you already have one
docker compose up -d --build --wait
docker compose exec app php artisan migrate --seed
```

On a fresh clone the app container runs `composer install` before it reports healthy, so create `auth.json` with your Nova credentials (below) first. On Linux, if your user ID isn't 1000, also give the images your IDs before the first build, so the files the containers create belong to you: `printf 'HOST_UID=%s\nHOST_GID=%s\n' "$(id -u)" "$(id -g)" >> .env`. If you change them later, run `docker compose down` and `docker volume rm kingboosting_frontend_node_modules` before rebuilding.

| Service           | URL                                                    |
| ----------------- | ------------------------------------------------------ |
| API + Nova admin  | http://localhost:8000                                  |
| Storefront (Nuxt) | http://localhost:3301                                  |
| Mailpit           | http://localhost:8025                                  |
| MySQL             | `localhost:3306`, user `kingboosting`, pass `secret`   |

-   The containers override the machine-specific values in `.env` (database, mail, URLs, Sanctum), so you don't need to edit it for Docker. Ports are published on `127.0.0.1` only.
-   Run tools inside the containers: `docker compose exec app php artisan tinker`, `docker compose exec frontend npm install <package>`.
-   Dependencies follow the lockfiles: the containers run `composer install` / `npm ci` on start whenever `composer.lock` / `package-lock.json` changed.
-   Xdebug: `XDEBUG_MODE=debug docker compose up -d app`, then start a session with an `XDEBUG_SESSION` cookie (e.g. a browser extension). Your IDE listens on port 9003 and maps the project root to `/var/www/html`.
-   Port taken? Set `APP_PORT`, `FRONTEND_PORT`, `FORWARD_DB_PORT` or `FORWARD_MAILPIT_DASHBOARD_PORT`.

## Nova credentials

Docker builds read Nova credentials from `auth.json` in the project root (gitignored), the same file `composer config http-basic.nova.laravel.com <username> <password>` writes:

```json
{
	"http-basic": {
		"nova.laravel.com": {
			"username": "<username>",
			"password": "<license key>"
		}
	}
}
```

## Production images

```shell
docker build --secret id=composer_auth,src=auth.json -t kingboosting-app .
docker build --secret id=composer_auth,src=auth.json --target web -t kingboosting-web .
docker build --build-arg HOST_URL=https://api.example.com --build-arg STRIPE_KEY=pk_live_... -t kingboosting-frontend frontend
```

-   `kingboosting-app` runs php-fpm as `www-data`. On start it caches config, routes and views; set `RUN_MIGRATIONS=true` to also run `migrate --force`. A new database also needs `php artisan db:seed --force` once (roles, permissions and an admin account whose password you should change).
-   `kingboosting-web` is nginx serving `public/` and passing PHP requests to `PHP_FPM_HOST` (default `app`), resolved through `NGINX_RESOLVER` (default `127.0.0.11`, Docker's DNS). Mount the app's storage volume at `/var/www/html/storage` so `/storage/*` URLs work.
-   The storefront compiles `HOST_URL`, `STRIPE_KEY` and `SENTRY_DSN` into its bundles, so they're build args and changing them needs a rebuild.
-   Outside `APP_ENV=local` the app generates `https://` URLs, so put a TLS-terminating proxy in front. It must pass the original `Host` header (nginx: `proxy_set_header Host $host;`) and set `X-Forwarded-For`/`X-Forwarded-Proto`.
-   Images built on Apple Silicon are arm64-only. Build on the server, or cross-build with `docker buildx build --platform linux/amd64 ...`.
-   API docs generation (`GENERATE_API_DOCS=true`) is off: it fails because the annotations in `docs/` aren't attached to classes, which swagger-php 4 requires.
-   The base images are end-of-life: `php:8.0-fpm-bullseye` (PHP 8.0, Debian 11) and Node 16, which Nuxt 2's webpack 4 needs. Upgrading them is the real fix.

`compose.prod.yaml` wires the images into a single-host stack with MySQL, a scheduler and a shared storage volume, configured through `.env.production` (start from `.env.production.example`). Usage is in the comments at the top of the file.
