# About this demo

King Boosting is an order marketplace for game boosting. Customers buy a boost, boosters claim and play the orders, and staff run the back office: payouts, fines, applications and support.

It was first built in 2020–2021 for a client. In 2026 it was rebuilt from scratch as a portfolio piece, so **nothing here is for sale**. Payments run in Stripe's test mode, and no one plays on your account.

## What to try

- **Sign in with a demo account.** The login page lists one account for each role: customer, booster, moderator and admin.
- **Place an order** as a customer and pay with Stripe's test card `4242 4242 4242 4242`.
- **Claim it** as a booster from the jobs board, then chat with the customer on the order page. Open both in two browsers to see messages and status changes arrive live.
- **Pause, resume and complete** the order, and watch each step land in the timeline and in the notifications.
- **Run the back office** as the admin: pay boosters in batches, issue fines, review applications and change roles.

## How it's built

- A **Laravel 13** JSON API. It uses Sanctum's cookie sessions and one policy layer for every role's permissions, and takes payments through Stripe.
- **Reverb** WebSockets for order chat and notifications.
- A **Nuxt 4** and **Vuetify** front end for the storefront and every role's dashboard.
- **Prices are worked out on the server**, from published rate tables in whole cents. The price you see is the price you pay, and a browser can't change it.

## The small print

League of Legends, Valorant and Wild Rift are trademarks of Riot Games, Inc. King Boosting isn't endorsed by Riot Games and isn't affiliated with it. Boosting breaks these games' terms of service, which is one more reason this demo only pretends to do it.
