# Architecture decision records

Short records of the choices made for the v2 rebuild (Laravel 13 API in `backend/`, Nuxt 4 in `frontend/`). Each one gives the context, the decision and its consequences. To change a decision, add a new record that supersedes the old one instead of editing it.

| # | Decision | Status |
| --- | --- | --- |
| [0001](0001-single-origin-routing.md) | Serve the API, Reverb and Nuxt from one origin, routed by path | Accepted |
| [0002](0002-role-column.md) | A `users.role` enum instead of Spatie roles and permissions | Accepted |
| [0003](0003-server-side-pricing.md) | Price tables as config, in integer euro cents, with a quote snapshot per order | Accepted |
| [0004](0004-original-art.md) | Original art instead of Riot Games assets | Accepted |
| [0005](0005-drop-sentry.md) | Drop Sentry for now | Accepted |
| [0006](0006-pint-house-style.md) | Pint, configured to the house style, in check-only mode | Accepted |
