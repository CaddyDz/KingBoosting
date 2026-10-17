# 0005. Drop Sentry for now

- Status: Accepted
- Date: 2026-10-17

## Context

The legacy app reported errors to Sentry from both apps (`sentry/sentry-laravel` and `@nuxtjs/sentry`). The storefront's DSN was compiled into the bundle at build time, and an earlier one was committed to the repo. The v2 demo is a single VPS with very little traffic, and each extra service is one more account and secret to manage.

## Decision

- v2 ships **without Sentry**. Errors go to Laravel's log (stderr in the containers) and to the browser console.
- If error tracking becomes worth it, add it back behind an env flag: the SDKs load only when `SENTRY_DSN` (backend) or `NUXT_PUBLIC_SENTRY_DSN` (frontend, read through `runtimeConfig` at runtime) is set. The images stay the same with or without it.

## Consequences

- One less dependency and one less secret on each side.
- The uptime check from #43 and `docker compose logs` are the only signals that something broke. For a demo that resets nightly, that's acceptable.
- Re-adding Sentry is a small, isolated change that needs no rebuild per environment.
