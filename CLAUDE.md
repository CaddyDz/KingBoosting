# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

King Boosting is a multi-role order marketplace for game-boosting services. This is the `v2` rebuild: a Laravel 13 JSON API in `backend/` (PHP 8.4) and a Nuxt 4 app in `frontend/` (the storefront plus every role's dashboard). The legacy app (Laravel 8 + Nova 3, and a Nuxt 2 storefront) lives on `develop` and the `v1.*` tags. Read it with `git show develop:<path>` when porting.

Decisions are recorded in `docs/adr/`. Read the relevant one before changing routing, roles, pricing, art, error tracking or style.

## Running things

Docker is the only way to run this locally; the host has no PHP. `README.md` covers setup.

```shell
docker compose up -d --build --wait
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan tinker
docker compose exec app composer require <package>
docker compose exec frontend npm install <package>      # npm 11: npm 10 crashes on this lockfile
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

Static analysis: `docker compose exec app ./vendor/bin/phpstan analyse` (Larastan, level 6). Larastan ignores the models' `casts()` here, so enum and date columns carry `@property` hints.

Frontend checks: `docker compose exec frontend npm run lint` (ESLint with tabs, single quotes, no semicolons), `npm run typecheck`, and `npm test` (Vitest: `test/unit` in Node, `test/nuxt` in a Nuxt environment).

End-to-end tests: `docker compose --profile e2e run --rm e2e` runs Playwright (`frontend/e2e`) against the running stack.
- They start by resetting the demo through `POST /api/testing/reset`, so they wipe your local data.
- `routes/testing.php` only exists when `APP_ENV` is `local` or `testing`.
- Without Stripe keys, a local stack pays orders through `OfflinePaymentGateway`. The payment page then offers "Simulate a successful payment", which does what Stripe's webhook would.
- On Linux, the container runs as `HOST_UID`/`HOST_GID` (see the README).

npm 11 refuses dependency install scripts unless `package.json`'s `allowScripts` approves them. When adding a package that needs one, run `npx npm@11 install-scripts approve <pkg>`, or `deny` it when a prebuilt binary covers it.

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

- `app` is php-fpm with the code bind-mounted. Its entrypoint runs `composer install` when `composer.lock` changes.
- `web` is nginx for `backend/public`, and `proxy` routes by path. The proxy listens on `APP_PORT` (8000) inside the network too, so a container sharing its network sees the same origin as your browser.
- `queue` runs `queue:listen`, so it picks up code changes. Notifications and broadcasts are queued, and nothing reaches Mailpit or the browser without it.
- `reverb` is the WebSocket server, started with `--debug`.
- `scheduler` runs `schedule:work`.
- `db` is MySQL 8.4; `mailpit` catches mail.
- `stripe` sits behind the `stripe` profile and forwards test webhooks.

### Production (`compose.prod.yaml`, `docs/deploy.md`)

The public demo runs the GHCR images behind the VPS's Traefik. The routes are the same as in development, with two differences:

- Reverb's router matches `/app/` with the trailing slash, so its `/apps` HTTP API stays internal.
- The `reverb`, `queue` and `scheduler` services override the app image's php-fpm healthcheck, because Traefik skips unhealthy containers.

Things production relies on:

- `demo:reset` seeds with model factories, so Faker is a production dependency, not a dev one.
- The app refuses live Stripe keys.
- Guests on `/api/*` always get a 401 JSON response, never a redirect.

Security headers live with each server:

- **Nuxt** (`routeRules` in `nuxt.config.ts`): `X-Robots-Tag`, `Referrer-Policy`, `Permissions-Policy`, `nosniff` and the frame policy on every page. In production it also sends the CSP, which allows only Stripe as a third party. Development has no CSP, because Nuxt's devtools need more.
- **nginx**: a `default-src 'none'` CSP on the API.
- **Traefik**: HSTS.

A new third-party script, frame or connection needs a CSP entry.

`REVERB_ALLOWED_ORIGINS` takes hostnames only: Reverb compares the Origin's host without its port.

### Frontend conventions

- `runtimeConfig` carries everything environment-specific (`NUXT_API_BASE`, `NUXT_PUBLIC_STRIPE_KEY`, `NUXT_PUBLIC_REVERB_*`). Nothing is baked in at build time.
- `/dashboard/**`, `/checkout/**` and the auth pages render on the client only. The storefront is server-rendered with SWR caching, so it must never render anything user-specific on the server. Signed-in bits (the header's account buttons) render in `<ClientOnly>`, and `useAuthStore().loadIfSignedIn()` skips the `/api/me` call when there's no session cookie. The cache is off in development, because Nitro keeps it on disk across restarts.
- Vuetify's composables are auto-imported with a `V` prefix (`useVDisplay`, `useVTheme`), because its `useLayout` collides with Nuxt's.
- Vuetify 4 uses the Material 3 type scale (`text-headline-small`, `text-body-medium`, `text-body-small`). The Material 2 classes (`text-h5`, `text-body-2`, `text-caption`) match nothing, and ESLint rejects them.
- `useAsyncData` returns a shallow ref in Nuxt 4. To change one row, assign a new `data.value`; mutating inside it doesn't re-render.
- Amounts from the API are euro cents; format them with `formatCents()` (`app/utils/money.ts`).
- One page, `pages/[service].vue`, is the order builder for every service, at the legacy URLs (`/division-boosting`). It's drawn from `GET /api/catalog/{service}`, so a new service needs only `config/pricing`. `utils/orderBuilder.ts` turns the builder's state into quote input, and the `order` store asks `POST /api/quotes` for prices, from the browser only.
- Checkout is two pages. `/checkout` signs the customer in and places the order. `/checkout/{order}` takes the payment with Stripe's Payment Element, and Stripe sends 3-D Secure returns back there. It then polls the order until the webhook has moved it on. Without `STRIPE_KEY` and `STRIPE_SECRET`, orders are saved unpaid and the page says payments are off.
- Charts are plain HTML and CSS (`components/overview/`), with no chart library. Marks use the theme's `chart` colour, which is checked for contrast and lightness against each theme's surface.

### Backend conventions

- **Policies are the only authorisation layer** (`docs/adr/0002`). Every user has one `role` (`admin`, `moderator`, `booster`, `customer`), and controllers, channels and API resources ask the policies.
- **Prices are calculated on the server** from the tables in `backend/config/pricing/`, in integer euro cents (`docs/adr/0003`). Never trust a price from the client.
- Demo data uses `@kingboosting.test` addresses. Never use `kingboosting.com`: that domain now runs a different site.
