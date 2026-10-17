<?php

declare(strict_types=1);

namespace App\Actions\Payouts;

use App\Support\Earnings;
use App\Exceptions\OrderConflict;
use App\Notifications\PayoutSettled;
use App\Actions\Orders\MarkOrderPaidOut;
use Illuminate\Support\Facades\{DB, Gate};
use App\Models\{Fine, Order, Payout, User};
use Illuminate\Database\Eloquent\Collection;

/**
 * Pay a booster everything they're owed: their unpaid completed orders move to paid_out, their unpaid
 * fines are deducted, and one Payout records it. With $orderIds, only those orders are paid and fines wait.
 */
class PayOutBooster
{
	public function __construct(private Earnings $earnings, private MarkOrderPaidOut $markPaidOut) {}

	/**
	 * @param  list<int>|null  $orderIds
	 */
	public function handle(User $admin, User $booster, ?array $orderIds = null): Payout
	{
		Gate::forUser($admin)->authorize('create', Payout::class);

		$payout = DB::transaction(function () use ($admin, $booster, $orderIds): Payout {
			$orders = $this->earnings->unpaidOrders($booster)
				->when($orderIds !== null, fn ($query) => $query->whereKey($orderIds))
				->lockForUpdate()
				->get();
			$fines = $orderIds === null
				? $this->earnings->unpaidFines($booster)->lockForUpdate()->get()
				: new Collection;

			if ($orders->isEmpty()) {
				throw new OrderConflict("{$booster->username} has no completed orders waiting for a payout.");
			}

			$gross = $this->earnings->shares($orders);
			$finesTotal = (int) $fines->sum('amount_cents');
			if ($gross - $finesTotal <= 0) {
				throw new OrderConflict("{$booster->username}'s fines are more than what they've earned; wait for more completed orders.");
			}

			$payout = Payout::query()->create([
				'booster_id' => $booster->id,
				'paid_by' => $admin->id,
				'gross_cents' => $gross,
				'fines_cents' => $finesTotal,
				'amount_cents' => $gross - $finesTotal,
				'orders_count' => $orders->count(),
				'paid_at' => now(),
			]);

			$orders->each(function (Order $order) use ($admin, $payout): void {
				$this->markPaidOut->handle($admin, $order);
				$order->newQuery()->whereKey($order->id)->update(['payout_id' => $payout->id]);
			});
			Fine::query()->whereKey($fines->modelKeys())->update(['payout_id' => $payout->id]);

			return $payout;
		});

		$booster->notify(new PayoutSettled($payout));

		return $payout;
	}
}
