# 0001. One origin, routed by path

- Status: Accepted
- Date: 2026-10-17

## Context

The legacy app ran on two hosts: the Nuxt storefront and the Laravel API + Nova backend. Sanctum's SPA cookie auth across two hosts needs a shared parent domain (`SESSION_DOMAIN=.example.com`), `SANCTUM_STATEFUL_DOMAINS`, CORS with credentials, and `SameSite` settings that survive Stripe's 3-D Secure redirect. Reverb (WebSockets) and its `/broadcasting/auth` endpoint need the same cookie on yet another path. Every one of these settings is a way for login to break.

The demo runs on a single VPS behind Traefik, at `kingboosting.dsalim.dev`.

## Decision

Serve everything from **one origin**, `https://kingboosting.dsalim.dev`, and let Traefik route by path:

| Path | Service |
| --- | --- |
| `/api`, `/sanctum`, `/broadcasting`, `/stripe`, `/up` | Laravel (nginx + php-fpm) |
| `/app` (the WebSocket) | Reverb. Its `/apps` HTTP API stays on the internal network only. |
| everything else | Nuxt (Nitro server) |

Local development mirrors this: the compose stack puts one proxy in front of Laravel, Reverb and Nuxt, so the browser also sees a single origin.

## Consequences

- No CORS configuration. Sanctum's cookies are host-only, `SESSION_DOMAIN` stays empty and `SANCTUM_STATEFUL_DOMAINS` is the one host.
- `SESSION_SAME_SITE=lax` is enough. The browser comes back from Stripe's 3-D Secure page with a top-level GET, which `lax` allows.
- Echo connects to `wss://kingboosting.dsalim.dev/app` on port 443, so no extra port or certificate is needed.
- One TLS certificate and one Traefik router per service, all on the same host rule.
- Nuxt's server-side rendering talks to Laravel over the internal network for public data (the catalog). Logged-in pages (`/dashboard/**`, `/checkout/**`) render on the client only, so SSR never needs to forward the session cookie.
- Laravel can't own `/`, and Nuxt can't use the paths listed above for pages.
