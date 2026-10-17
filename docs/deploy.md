# Deploying the demo

The public demo runs at **https://kingboosting.dsalim.dev** on one VPS. It sits behind the Traefik that already serves the server's other sites, and uses the images CI pushes to GHCR. `compose.prod.yaml` describes the stack:

| Service | Image | Job |
| --- | --- | --- |
| `app` | `kingboosting-app` | php-fpm. It runs the migrations when it starts. |
| `web` | `kingboosting-web` | nginx in front of php-fpm. Traefik sends it `/api/`, `/sanctum/`, `/broadcasting/`, `/stripe/` and `/up`. |
| `reverb` | `kingboosting-app` | WebSockets on `/app/`. Its `/apps` API is only reachable on the internal network. |
| `queue` | `kingboosting-app` | Sends notifications and broadcasts. |
| `scheduler` | `kingboosting-app` | Prunes unpaid orders daily, and runs `demo:reset` at 04:00 UTC. |
| `frontend` | `kingboosting-frontend` | Nuxt: everything else. |
| `db` | `mysql:8.4` | The database, in the `db_data` volume. |
| `backup` | `mysql:8.4` | Dumps the database to `./backups` at 03:30 UTC and keeps a week of dumps. |

## What the server needs

- **Docker** with Compose v2.
- **A Traefik** on an external Docker network, with an HTTPS entrypoint and a certificate resolver. Docker Engine 29 needs Traefik 3.6 or later: older releases ask for an API version the engine refuses.
- **A DNS `A` record** for `kingboosting.dsalim.dev` pointing at the server.
- **Access to the images.** Either make the three GHCR packages public, or run `docker login ghcr.io` with a token that has `read:packages`.

## First deploy

```shell
mkdir -p /srv/kingboosting/docker/backup /srv/kingboosting/backups && cd /srv/kingboosting
# From the repository: compose.prod.yaml, docker/backup/backup.sh and .env.production.example
cp .env.production.example .env.production
```

Fill in `.env.production`:

- `APP_KEY`: `echo "base64:$(openssl rand -base64 32)"`
- `DB_PASSWORD`, `DB_ROOT_PASSWORD`: `openssl rand -hex 24`
- `REVERB_APP_KEY`: `openssl rand -hex 16`. `REVERB_APP_SECRET`: `openssl rand -hex 32`.
- `TRAEFIK_NETWORK`, `TRAEFIK_ENTRYPOINT`, `TRAEFIK_CERTRESOLVER`: the names the server's Traefik uses.
- The Stripe **test** keys, then the webhook secret from the next section. The app refuses live keys.

Then start the stack and load the demo data:

```shell
docker compose -f compose.prod.yaml --env-file .env.production up -d --wait
docker compose -f compose.prod.yaml --env-file .env.production exec app php artisan demo:reset --force
```

## Stripe, in test mode

1. In the Stripe dashboard, with **test mode** on, add a webhook endpoint for `https://kingboosting.dsalim.dev/stripe/webhook`.
2. Subscribe it to `payment_intent.succeeded` and `payment_intent.payment_failed`.
3. Put its signing secret (`whsec_...`) in `STRIPE_WEBHOOK_SECRET`.
4. Run `up -d` again, so the containers pick up the new setting.

## Deploys from CI

After the images are pushed, the `deploy` job in `.github/workflows/ci.yml` runs on every push to `develop`:

1. It copies `compose.prod.yaml` and the backup script to the server over SSH.
2. It sets `IMAGE_TAG` to the commit's image tag.
3. It pulls the images and runs `up -d`.

It needs a GitHub environment called `production` with these secrets:

| Secret | Value |
| --- | --- |
| `DEPLOY_HOST` | The server's address |
| `DEPLOY_USER` | A user in the `docker` group |
| `DEPLOY_SSH_KEY` | A private key that user accepts, used only for deploys |
| `DEPLOY_KNOWN_HOSTS` | The output of `ssh-keyscan <host>`, so the job can trust the server |

To roll back, set `IMAGE_TAG` in `.env.production` to an earlier `sha-...` tag and run `up -d`.

## Smoke checklist

On the public URL, after each deploy:

- [ ] Log in as each role: `admin@`, `moderator@`, `booster@` and `customer@kingboosting.test`, password `password`. Each lands on its dashboard.
- [ ] As a guest, build an order, sign in at checkout, and pay with `4242 4242 4242 4242`. The order opens with "Payment received".
- [ ] Repeat with `4000 0027 6000 3184`, which asks for 3-D Secure first.
- [ ] As the booster, claim the paid order from the jobs board.
- [ ] Chat between the customer and the booster in two browsers. Messages arrive live and show "Seen".

Everything except the two payments was checked on a local copy of this stack: the production images behind Traefik 3.7 over HTTPS, with live chat through `wss://`. The payments need the Stripe test keys.

## Backups and restores

The dumps are `./backups/kingboosting-YYYY-MM-DD.sql.gz`. Remember that the nightly reset wipes the demo anyway. To restore one:

```shell
gunzip -c backups/kingboosting-2026-10-17.sql.gz | docker compose -f compose.prod.yaml --env-file .env.production exec -T db sh -c 'mysql -uroot -p"$MYSQL_ROOT_PASSWORD" kingboosting'
```
