# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

King Boosting is an elo-boosting storefront. It has two apps: a Laravel 8 + Nova 3 backend at the repo root (PHP 8.0) and a Nuxt 2 storefront in `frontend/` (Node 14).

## Running things

Docker is the only way to run this locally. The host has no PHP, and its Node is too new for Nuxt 2/webpack 4. Setup, ports, Xdebug and production images are covered in `README.md`. Nova needs license credentials in a gitignored `auth.json` before the first build.

```shell
docker compose up -d --build --wait
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan tinker
docker compose exec frontend npm install <package>
```

The Docker Desktop VM is shared with other projects' stacks, so don't run `docker system prune` or similar.

### Tests

```shell
docker compose exec app php artisan test --parallel                          # what CI runs
docker compose exec app php artisan test --filter test_sanctum_authed_user   # single test
docker compose exec app php artisan test tests/Feature/UserTest.php          # single file
```

- `phpunit.xml` forces sqlite `:memory:`, so tests ignore the compose MySQL settings.
- The base `Tests\TestCase` uses `DatabaseMigrations` and calls `withoutExceptionHandling()`, so failures throw instead of returning 500s.
- Helpers: the global `create(Model::class, [...], 'create'|'make', $times)` from `tests/helpers/functions.php`, and `$this->login($user = null)`. For API auth, use `Sanctum::actingAs(...)`.

### Style

php-cs-fixer isn't a dev dependency. CI runs it via the `oskarstark/php-cs-fixer-ga` image using `.php-cs-fixer.php`, then **auto-commits "Fix styling" to the PR branch**, so pull before pushing again. To avoid that churn, write code in the house style:

- Tab indentation (`.editorconfig`; YAML uses 2 spaces).
- `declare(strict_types=1);` in every PHP file.
- Grouped imports (`use App\Models\{Order, User};`) sorted by **length**, not alphabetically.
- Arrow functions where possible.

### Other commands

- `php artisan make:entity {Model}` scaffolds a model, migration, resource controller, factory, seeder, API resource, test, `Create/Update/Delete{Model}Request`, Nova resource, policy and observer in one go.
- `composer ide` regenerates the IDE helper files.
- `php artisan clean` deletes the cache and IDE helper files.

### CI (`.github/workflows`)

Tests and style only run on PRs into `develop`, which is the main branch. On every push, TODO-to-Issue **opens a GitHub issue for each `TODO` comment** in the diff. A `[skip ci]` commit message skips the workflows.

## Architecture

### Nova is the whole logged-in app

`config/nova.php` mounts Nova at `/`. Apart from `/api/*` and the routes in `routes/web.php`, the backend domain *is* Nova, and every role uses it: Admin, Moderator, Booster and Member (customers). It isn't an admin-only panel. `resources/js/redirect.js` and `Nova\LoginController::redirectPath()` send everyone to `/resources/orders`.

Outside `local`, the Nova login form redirects to `FRONTEND_URL`. Users authenticate through the Nuxt storefront with Sanctum's session cookie and then use Nova on the same session.

### Routing quirks (`RouteServiceProvider`)

- `routes/api.php` is prefixed with `/api` but runs in the **`web`** middleware group. That's session-based Sanctum SPA auth, not token auth.
- `routes/web.php` has the authed `orders` and `messages/send` endpoints, plus `register`.
- `routes/dev.php` (debug/mailable previews) only loads outside production.
- `VerifyCsrfToken::$except = ['*']`: CSRF is disabled app-wide.

### Authorization

There are two layers, and both matter:

- **Spatie roles/permissions** (`vyuldashev/nova-permission`). Roles are seeded in `RoleSeeder`. Permission names live in the `Database\Data\Permissions` trait.
- **Inline role checks in Nova resources.** `indexQuery()` scopes rows per role: Members see their own orders, Boosters see pending orders plus their own. Fields and actions are gated with `->canSee()`/`->canRun()` using `hasRole(...)`. Most access rules live here, not in `app/Policies`.

Policies are auto-discovered by name (the `AuthServiceProvider` map is intentionally empty). The `viewNova` gate is open to all authenticated users.

Several Nova resources share a model, so a model change affects all of them:

- `Booster` is `User` scoped to the Booster role.
- `Payout` is `Order` scoped to `status = completed`.

### Order lifecycle

The statuses are `pending → progress ⇄ paused → completed → paid`. Three places mutate them:

- **`OrderObserver`** (registered in `ObserversServiceProvider`). On create it sets `client_id` from the authed user, sets `progress` if a booster was preselected, and creates the order's `Chat`. On update, a `pending` status clears `booster_id`.
- **Nova actions** in `app/Nova/Actions`: Assign, Release, Pause, Continue, Close, MarkAsPaid, etc.
- **The `LockOrder` listener**, which handles nova-button's `ButtonClick` event with key `lock-order`. A booster claims a pending order this way.

Order creation (`OrdersController@store`) charges via Stripe, then notifies either the chosen booster or every Booster (`OrderPlaced`). The receipt email is commented out.

Activity on orders is logged via `spatie/activitylog` and shown in Nova through `bolechen/nova-activitylog`.

### Chat

Order chat is an in-repo Nova tool at `nova-components/NovaChat`, pulled in as a composer path repository (`sitando/nova-chat`).

`MessagesController` broadcasts `SendMessage` on the private channel `channel.messages.{recipientId}`. The channel definitions are in `routes/channels.php`.

Docker dev sets the broadcaster to the string `"null"`, so real-time chat needs Pusher keys.

### Seeding

`DatabaseSeeder` always seeds permissions and roles. In `local` it then adds demo users, games, servers and orders. Any other environment gets only `BaseSeeder`, which creates one admin account with the password from `ADMIN_PASSWORD` (required outside `local`).

### Frontend (`frontend/`)

- Auth uses `@nuxtjs/auth-next` with the `laravelSanctum` strategy against `HOST_URL`.
- `HOST_URL`, `STRIPE_KEY` and `SENTRY_DSN` are compiled into the bundles at build time. Changing them needs a rebuild.
- `config/router.js` strips the `/services` prefix, so `pages/services/division-boosting.vue` is served at `/division-boosting`.
- Components are auto-imported from the dirs listed in `nuxt.config.js`.
- Pricing and order-building state lives in the Vuex modules under `store/`.

### Stale / known-broken

- API docs generation (`l5-swagger`, the annotations in `docs/`) fails under swagger-php 4 and is switched off.
