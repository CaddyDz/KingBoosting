# 0004. Original art instead of Riot Games assets

- Status: Accepted
- Date: 2026-10-17

## Context

The legacy storefront uses Riot Games artwork throughout: rank and mastery emblems, champion splash art in the game cards and service banners, and champion images hotlinked from `ddragon.leagueoflegends.com`.

Riot's "Legal Jibber Jabber" policy lets fan projects use some Riot assets, but only if they're free and don't look official, and it doesn't cover Riot's logos without a licence. Boosting also breaks the games' terms of service. A site that looks like a store selling boosts doesn't fit the policy, even as a demo with test payments.

## Decision

- Use **original or CC0 art** only: an abstract background, plain game cards, and **SVG rank badges drawn for the project** (simple tier shapes and colours, not copies of Riot's emblems).
- Name the games in plain text only, without their logos.
- Stop hotlinking Data Dragon. Champions appear by name, without portraits.
- Every page shows a footer: "Portfolio demo · not affiliated with Riot Games · test payments only".

## Consequences

- #39 replaces the rank and mastery emblems, the game cards, the service banners and the champion art. Removing them also accounts for most of the drop in `public/` size.
- The storefront looks less like a gaming site and more like a product demo, which is the point of the portfolio piece.
- The rank badges are ours, so they can be inline SVG and themed with the app's colours.
