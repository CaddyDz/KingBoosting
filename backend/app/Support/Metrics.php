<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Support\Carbon;

/**
 * The admin dashboard's numbers. Worked out in PHP rather than SQL date functions, so they're the same on
 * MySQL and on the sqlite the tests use; the demo's order volume makes that cheap.
 */
class Metrics
{
	/**
	 * Revenue per week from paid orders, oldest week first, with empty weeks as zero.
	 *
	 * @return list<array{week: string, revenue_cents: int, orders: int}>
	 */
	public function revenuePerWeek(int $weeks = 12): array
	{
		$start = now()->startOfWeek()->subWeeks($weeks - 1);
		$paid = Order::query()->whereNotNull('paid_at')->where('paid_at', '>=', $start)->get(['paid_at', 'price_cents']);

		$series = [];
		for ($week = $start->copy(); $week->lessThanOrEqualTo(now()); $week->addWeek()) {
			$series[$week->toDateString()] = ['week' => $week->toDateString(), 'revenue_cents' => 0, 'orders' => 0];
		}
		foreach ($paid as $order) {
			/** @var Carbon $paidAt */
			$paidAt = $order->paid_at;
			$key = $paidAt->copy()->startOfWeek()->toDateString();
			$series[$key]['revenue_cents'] += $order->price_cents;
			$series[$key]['orders']++;
		}

		return array_values($series);
	}

	/**
	 * @return array<string, int> status => number of orders, every status included
	 */
	public function ordersByStatus(): array
	{
		$counts = Order::query()->toBase()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

		return collect(OrderStatus::cases())
			->mapWithKeys(fn (OrderStatus $status): array => [$status->value => (int) ($counts[$status->value] ?? 0)])
			->all();
	}

	/**
	 * Average hours from claim to completion over the last 90 days, or null when nothing was completed.
	 */
	public function averageCompletionHours(): ?float
	{
		$durations = Order::query()
			->whereNotNull('claimed_at')
			->whereNotNull('completed_at')
			->where('completed_at', '>=', now()->subDays(90))
			->get(['claimed_at', 'completed_at'])
			->map(fn (Order $order): float => (float) $order->claimed_at?->diffInMinutes($order->completed_at) / 60);

		return $durations->isEmpty() ? null : round($durations->avg(), 1);
	}
}
