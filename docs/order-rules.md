# Order rules

Who can do what to an order, by status. `App\Policies\OrderPolicy` implements this table and `tests/Feature/Policies/OrderPolicyTest.php` checks every cell. The legacy app enforced none of it consistently (see `docs/domain.md`).

## Roles

- Registration always creates a **customer**. Staff roles are only given by an Admin.
- **Admin:** everything, including prices, payouts and fines.
- **Moderator:** support staff. Sees and manages every order and its chat, but never prices, payouts, fines or Riot credentials.
- **Booster:** sees the jobs board (pending orders, without credentials) and the orders they hold.
- **Customer:** sees their own orders only.

## Lifecycle

```
awaiting_payment ──► pending ──► in_progress ⇄ paused ──► completed ──► paid_out
        │               ▲             │          │
        │               └── release ──┴──────────┘
        └──► in_progress   (paid, with a booster picked at checkout)
```

`OrderStatus::transitions()` lists the allowed moves. Claiming is atomic: it succeeds only while the order is still `pending` with no booster.

## Abilities

"Holder" is the booster set on the order once it's paid; "owner" is the customer who placed it. A blank cell is never allowed.

| Ability | Admin | Moderator | Booster | Customer |
| --- | --- | --- | --- | --- |
| `view` | all | all | `pending` orders, and orders they hold | own |
| `viewCredentials` | all | | held, while `in_progress` or `paused` | own |
| `viewPrice` | all | | | own |
| `viewBoosterShare` | all | | `pending`, and orders they hold | |
| `claim` | | | `pending` with no booster | |
| `assign` | `pending` | `pending` | | |
| `pause` | `in_progress` | `in_progress` | holder, `in_progress` | owner, `in_progress` |
| `resume` | `paused` | `paused` | holder, `paused` | owner, `paused` |
| `release` | `in_progress`, `paused` | `in_progress`, `paused` | holder, `in_progress`, `paused` | |
| `complete` | | | holder, `in_progress` | |
| `markPaidOut` | `completed` | | | |
| `updateCredentials` | | | | owner, until `completed` |
| `pay` | | | | owner, while `awaiting_payment` |
| `chat` | paid orders | paid orders | orders they hold | own, once paid |

When a user could take an action but the order's status doesn't allow it (it was claimed a moment ago, say), the policy answers **409** with a reason. Every other refusal is a **403**.

## Money

- Prices are calculated on the server from `backend/config/pricing/`, in euro cents (`docs/adr/0003`). The client's price is never used.
- A booster earns `price × booster_share_pct / 100` per completed order, rounded half up to the cent.
- The amount owed to a booster is their unpaid completed shares minus their unpaid fines.
- A booster picked at checkout must be a visible Booster. The order then goes straight to `in_progress` once paid.

## Chat

One private channel per order, authorised by `chat`. Every order action posts a system message, and staff messages show as "Support".
