# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

King Boosting is a multi-role order marketplace for game-boosting services. This is the `v2` rebuild: a Laravel 13 JSON API in `backend/` (PHP 8.4) and a Nuxt app in `frontend/`. Until the Nuxt 4 rewrite lands, `frontend/` is still the legacy Nuxt 2 storefront, kept as the source for porting the price tables. The legacy Laravel 8 + Nova 3 app lives on `develop` (and the `v1.*` tags); read it with `git show develop:<path>` when porting.

Decisions are recorded in `docs/adr/`. Read the relevant one before changing routing, roles, pricing, art, error tracking or style.

## Running things

Docker is the only way to run this locally; the host has no PHP. `README.md` covers setup.

```shell
docker compose up -d --build --wait
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan tinker
docker compose exec app composer require <package>
```

The Docker VM is shared with other projects' stacks, so don't run `docker system prune` or similar.

Laravel Boost is installed. Its guidelines are in `backend/CLAUDE.md` and its skills in `backend/.claude/skills/`. Where they say to run `php artisan ...` or `vendor/bin/pint ...`, run it through `docker compose exec app`. The root `.mcp.json` starts Boost's MCP server the same way.

### Tests

```shell
docker compose exec app php artisan test --compact                       # the whole suite
docker compose exec app php artisan test --compact --filter=claims       # by name
docker compose exec app php artisan test --compact tests/Feature/HealthTest.php
```

- Tests use Pest. Feature tests get `RefreshDatabase` from `tests/Pest.php`.
- `phpunit.xml` forces sqlite `:memory:` with `<server ... force="true">`. Plain `<env>` entries would lose to the MySQL variables compose sets, and `RefreshDatabase` would then wipe the dev database.

### Style

Pint, configured in `backend/pint.json` (see `docs/adr/0006`). The house style:

- Tab indentation (`.editorconfig`; YAML uses 2 spaces). Pint can't enforce tabs, so its indentation rules are off and it leaves them alone.
- `declare(strict_types=1);` in every PHP file.
- Grouped imports (`use App\Models\{Order, User};`) sorted by **length**, not alphabetically.
- Arrow functions where possible.

The `make:*` stubs in `backend/stubs/` are published with tabs and strict types, so generated files already match. Run `docker compose exec app ./vendor/bin/pint --dirty` after editing PHP.

Static analysis: `docker compose exec app ./vendor/bin/phpstan analyse` (Larastan, level 6).

### CI (`.github/workflows`)

The workflows are still the legacy ones, built for the Nova app at the repository root, until #41 replaces them. On every push, TODO-to-Issue **opens a GitHub issue for each `TODO` comment** in the diff, so don't leave `TODO`s in code. A `[skip ci]` commit message skips the workflows.

## Architecture

### One origin (`docs/adr/0001`)

The browser only ever talks to one origin. In development that's the `proxy` service on http://localhost:8000; in production it's Traefik. Both route by path:

| Path | Goes to |
| --- | --- |
| `/api`, `/sanctum`, `/broadcasting`, `/stripe`, `/up` | Laravel (`web` nginx → `app` php-fpm) |
| `/app` | Reverb (the WebSocket) |
| everything else | Nuxt |

So Sanctum's SPA cookie auth needs no CORS and no shared cookie domain: `SANCTUM_STATEFUL_DOMAINS` is the one host, and `SESSION_SAME_SITE=lax`. `routes/web.php` is empty on purpose. Laravel serves no pages.

### Compose services

`app` (php-fpm, code bind-mounted), `web` (nginx for `backend/public`), `proxy`, `db` (MySQL 8.4), `mailpit`, and `stripe` (behind the `stripe` profile, forwards test webhooks). The dev entrypoint runs `composer install` when `composer.lock` changes.

### Backend conventions

- **Policies are the only authorisation layer** (`docs/adr/0002`). Every user has one `role` (`admin`, `moderator`, `booster`, `customer`), and controllers, channels and API resources ask the policies.
- **Prices are calculated on the server** from the tables in `backend/config/pricing/`, in integer euro cents (`docs/adr/0003`). Never trust a price from the client.
- Demo data uses `@kingboosting.test` addresses. Never use `kingboosting.com`: that domain now runs a different site.
