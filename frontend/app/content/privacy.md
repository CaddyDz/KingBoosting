# Privacy

*Last updated 17 October 2026*

King Boosting is a portfolio demo. It runs no real business, so the simplest advice comes first: **don't enter anything real**. Use a made-up email address, and never type the login of a game account you care about.

## What the demo stores

- **Your account:** username, email address and a hash of your password. The password itself is never stored.
- **Orders:** what you bought, the game account name and login you entered, and the order's timeline. The login and password are encrypted in the database. They're shown only to you, to the booster playing the order while it's in progress or paused, and to the admins.
- **Chat messages** on your orders, and the notifications about them.
- **Booster applications** and **contact messages**. Admins read these to reply.

## Payments

Checkout uses Stripe in test mode. Card details go straight from your browser to Stripe and never reach this site's servers, and real cards are never charged.

## Cookies

Two cookies keep you signed in and protect forms: the session cookie and the `XSRF-TOKEN` cookie. There's no analytics, no advertising and no tracking.

## How long it's kept

- **Unpaid checkouts** are deleted after a day.
- **Everything else** is deleted whenever the public demo is reset, which happens regularly, and possibly before you're done with it.
- **IP addresses** are held briefly by the rate limiter, which caps how often forms can be sent. They expire within the hour.

## Questions

Use the [contact form](/contact).
