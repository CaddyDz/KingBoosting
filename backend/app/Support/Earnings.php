<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\OrderStatus;
use App\Models\{Fine, Order, User};
use Illuminate\Database\Eloquent\{Builder, Collection};

/**
 * What a booster is owed: the shares of their completed orders not paid out yet, minus their unpaid fines.
 * (The legacy payout Total kept only the last order's share and never took fines off.)
 */
class Earnings
{
	/**
	 * @return Builder<Order>
	 */
	public function unpaidOrders(User $booster): Builder
	{
		return Order::query()
			->where('booster_id', $booster->id)
			->where('status', OrderStatus::Completed)
			->whereNull('payout_id');
	}

	/**
	 * @return Builder<Fine>
	 */
	public function unpaidFines(User $booster): Builder
	{
		return Fine::query()->where('booster_id', $booster->id)->whereNull('payout_id');
	}

	/**
	 * @return array{orders_count: int, share_cents: int, fines_cents: int, owed_cents: int}
	 */
	public function summary(User $booster): array
	{
		/** @var Collection<int, Order> $orders */
		$orders = $this->unpaidOrders($booster)->get(['id', 'price_cents', 'booster_share_pct']);
		$share = $this->shares($orders);
		$fines = (int) $this->unpaidFines($booster)->sum('amount_cents');

		return [
			'orders_count' => $orders->count(),
			'share_cents' => $share,
			'fines_cents' => $fines,
			'owed_cents' => $share - $fines,
		];
	}

	/**
	 * Each order's share is rounded on its own, the way the order page shows it.
	 *
	 * @param  Collection<int, Order>  $orders
	 */
	public function shares(Collection $orders): int
	{
		return $orders->sum(fn (Order $order): int => $order->boosterShareCents());
	}
}
