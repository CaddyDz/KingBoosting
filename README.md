# King Boosting

A multi-role order marketplace: customers buy game-boosting services, boosters claim and complete them, and staff run the back office. Built in 2020–2021 for a client, and being rebuilt in 2026 on this `v2` branch.

The full README comes with the rebuild (#44). Until then, this is how to run what exists.

## Layout

| Path | What it is |
| --- | --- |
| `backend/` | Laravel 13 JSON API on PHP 8.4: Sanctum SPA auth, Reverb, Stripe |
| `frontend/` | Nuxt 4 + Vuetify: the storefront and every role's dashboard |
| `docs/adr/` | Decision records for the rebuild |
| `compose.yaml` | The local stack |

The legacy Laravel 8 + Nova 3 app and its Nuxt 2 storefront are on `develop` and will be tagged `v1.0-delivered` and `v1.9-legacy`.

## Running it

Docker is the only requirement.

```shell
docker compose up -d --build --wait
docker compose exec app php artisan migrate --seed
```

On Linux, if your user ID isn't 1000, give the images your IDs before the first build so files the containers create belong to you: `printf 'HOST_UID=%s\nHOST_GID=%s\n' "$(id -u)" "$(id -g)" >> .env`.

| URL | Service |
| --- | --- |
| http://localhost:8000 | The app. `/api`, `/sanctum`, `/broadcasting`, `/stripe` and `/up` go to Laravel, `/app` to Reverb, the rest to Nuxt |
| http://localhost:8025 | Mailpit |

If you ran the legacy stack before, its database volume has the old schema: `docker compose down -v` once to start fresh.

## Tests and checks

```shell
docker compose exec app php artisan test
docker compose exec app ./vendor/bin/pint --test
docker compose exec app ./vendor/bin/phpstan analyse
docker compose exec frontend npm run lint
docker compose exec frontend npm run typecheck
docker compose exec frontend npm test
docker compose --profile e2e run --rm e2e   # Playwright against the running stack; resets the demo data first
```

Without Stripe keys, a local stack still takes orders: payments run offline, and the payment page offers to simulate Stripe's answer.

## Deploying

The public demo at https://kingboosting.dsalim.dev runs `compose.prod.yaml` behind Traefik: see [docs/deploy.md](docs/deploy.md).
