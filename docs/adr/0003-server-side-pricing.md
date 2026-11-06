# 0003. Price tables as config, in integer euro cents

- Status: Accepted
- Date: 2026-10-17

## Context

In the legacy app, every service page priced the order in the browser from JavaScript tables (`frontend/assets/js/*`). The page then posted the price, and `OrdersController@store` charged it as is, so a customer could set their own price. The prices were floats in what the page labelled USD, and the backend charged the same number in EUR. `orders.price` was `decimal(5,2)`, which can't hold more than 999.99.

The rebuild prices orders on the server. The tables change rarely, and only together with the code that interprets them.

## Decision

- The price tables live in **config files**, `backend/config/pricing/{lol,valorant,wildrift}/*.php`, versioned with the code. Display-only data (icons, colours, box shadows) stays in the frontend.
- Every amount is an **integer number of euro cents**. EUR is the only currency: it's what Stripe charges, and what the storefront shows.
- The quote engine adds up cents and applies the option percentages as exact fractions, then rounds **once**, half up, to whole cents. That reproduces the legacy prices exactly (see `docs/pricing-golden.json`, where several totals land on half a cent).
- Each order stores a **snapshot** of its quote: the inputs, each line of the breakdown, the option percentages and the total. `price_cents` is that total.
- `POST /api/quotes` returns the same breakdown the order will store, so the UI never calculates a price.

## Consequences

- The client's price is never trusted. A test proves that a price sent with an order is ignored.
- Changing a price is a code change and a deploy. That's fine for a demo; an admin price editor would mean moving the tables to the database.
- Past orders keep the price and breakdown they were sold at, even after the tables change.
- `php artisan config:cache` caches the tables with the rest of the config.
- The golden quotes recorded from the legacy storefront are the acceptance test for the engine.
