<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use App\Enums\{OrderEventType, OrderStatus};
use App\Models\{Application, Fine, Message, Order, User};
use App\Actions\Orders\{AssignOrder, ClaimOrder, CompleteOrder, MarkOrderPaidOut, PauseOrder, ReleaseOrder};

/**
 * The public demo: named accounts for every role and about 40 orders in every status, game and service,
 * spread over the last eight weeks. Orders go through the real action classes, so their timelines exist.
 */
class DemoSeeder extends Seeder
{
	/**
	 * What customers bought: one entry per service, priced like the legacy storefront (docs/pricing-golden.json).
	 *
	 * @var list<array{game: string, service: string, title: string, server: string, cents: int, options: list<array{key: string, label: string, percent: int}>}>
	 */
	private const PURCHASES = [
		['game' => 'lol', 'service' => 'division-boosting', 'title' => 'Bronze I → Gold III', 'server' => 'euw', 'cents' => 13425, 'options' => []],
		['game' => 'lol', 'service' => 'division-boosting', 'title' => 'Platinum II → Diamond IV', 'server' => 'eune', 'cents' => 22200, 'options' => [['key' => 'priority', 'label' => 'Priority order (2x speed)', 'percent' => 20]]],
		['game' => 'lol', 'service' => 'duoqueue-boosting', 'title' => 'Silver IV → Gold IV, duo', 'server' => 'euw', 'cents' => 12090, 'options' => [['key' => 'coaching', 'label' => 'With coaching', 'percent' => 30]]],
		['game' => 'lol', 'service' => 'placement-boosting', 'title' => '6 placement games (Gold II)', 'server' => 'na', 'cents' => 3078, 'options' => [['key' => 'play-with-booster', 'label' => 'Play with booster', 'percent' => 35]]],
		['game' => 'lol', 'service' => 'ranked-wins-boosting', 'title' => '6 ranked wins (Gold II)', 'server' => 'euw', 'cents' => 4872, 'options' => [['key' => 'play-with-booster', 'label' => 'Play with booster', 'percent' => 40]]],
		['game' => 'lol', 'service' => 'champion-mastery', 'title' => 'Ahri mastery 5 → 7', 'server' => 'euw', 'cents' => 5000, 'options' => []],
		['game' => 'valorant', 'service' => 'valorant-boosting', 'title' => 'Bronze I → Gold III', 'server' => 'eu', 'cents' => 12480, 'options' => []],
		['game' => 'valorant', 'service' => 'valorant-placement-boosting', 'title' => '3 placement games (Platinum II)', 'server' => 'eu', 'cents' => 10350, 'options' => [['key' => 'streaming', 'label' => 'With streaming', 'percent' => 15]]],
		['game' => 'valorant', 'service' => 'unrated-matches', 'title' => '15 unrated matches', 'server' => 'na', 'cents' => 7140, 'options' => [['key' => 'net-wins', 'label' => 'Net wins', 'percent' => 40]]],
		['game' => 'wildrift', 'service' => 'wildrift-rank-boosting', 'title' => 'Gold IV → Platinum IV', 'server' => 'europe', 'cents' => 12180, 'options' => [['key' => 'priority', 'label' => 'Priority order (2x speed)', 'percent' => 20]]],
		['game' => 'wildrift', 'service' => 'wildrift-placement-boosting', 'title' => '3 placement games (Silver III)', 'server' => 'europe', 'cents' => 5256, 'options' => [['key' => 'priority', 'label' => 'Priority order', 'percent' => 20]]],
		['game' => 'wildrift', 'service' => 'wildrift-ranked-wins-boosting', 'title' => '8 ranked wins (Platinum IV)', 'server' => 'americas', 'cents' => 6200, 'options' => []],
	];

	/**
	 * How many orders end in each status.
	 */
	private const STATUSES = [
		'awaiting_payment' => 2,
		'pending' => 8,
		'in_progress' => 9,
		'paused' => 4,
		'completed' => 9,
		'paid_out' => 8,
	];

	private const CHAT = [
		['customer', 'Hi! I can leave the account to you from 8pm CET tonight.'],
		['booster', 'Perfect, I will start at 8pm and message you when I log off.'],
		['booster', 'Two wins so far, the first game was close. Back tomorrow evening.'],
		['customer', 'Great, thanks for the update!'],
	];

	/**
	 * When the seeder started. Every date is relative to it, because now() follows Carbon::setTestNow().
	 */
	private Carbon $startedAt;

	private User $admin;

	private User $moderator;

	/** @var list<User> */
	private array $boosters;

	/** @var list<User> */
	private array $customers;

	public function run(): void
	{
		$this->startedAt = now();
		$this->seedUsers();
		$this->seedOrders();
		$this->seedFines();
		Application::factory()->count(2)->create();
		Application::factory()->reviewed()->create();
	}

	private function seedUsers(): void
	{
		$account = fn (string $username, string $role, array $extra = []): User => User::factory()->{$role}()->create([
			'username' => $username,
			'email' => $username.'@kingboosting.test',
			'password' => config('demo.password'),
			...$extra,
		]);

		$this->admin = $account('admin', 'admin');
		$this->moderator = $account('moderator', 'moderator');
		$this->boosters = [
			$account('booster', 'booster'),
			$account('nightowl', 'booster'),
			$account('ghostrider', 'booster', ['visible' => false]),
		];
		$this->customers = [
			$account('customer', 'customer'),
			$account('ranked_dreamer', 'customer'),
			$account('silver_surfer', 'customer'),
			$account('duo_partner', 'customer'),
		];
	}

	private function seedOrders(): void
	{
		$index = 0;
		foreach (self::STATUSES as $status => $count) {
			for ($rank = 0; $rank < $count; $rank++) {
				$this->seedOrder($index++, OrderStatus::from($status), $rank);
			}
		}

		Carbon::setTestNow();
	}

	/**
	 * Walk one order through its life up to the given status, at plausible times in the past.
	 * $rank is the order's position among those ending in the same status, to spread their dates.
	 */
	private function seedOrder(int $index, OrderStatus $status, int $rank): void
	{
		$purchase = self::PURCHASES[$index % count(self::PURCHASES)];
		// The seeded customer and booster get the most orders, so their screens are the fullest
		$customer = $index % 3 === 0 ? $this->customers[0] : $this->customers[$index % count($this->customers)];
		$booster = $index % 2 === 0 ? $this->boosters[0] : $this->boosters[1 + ($index % 2)];
		// Finished orders are older; the jobs board and unpaid checkouts are recent
		$daysAgo = match ($status) {
			OrderStatus::PaidOut => 24 + $rank * 4,
			OrderStatus::Completed => 6 + $rank * 2,
			OrderStatus::InProgress, OrderStatus::Paused => 1 + $rank % 6,
			OrderStatus::Pending, OrderStatus::AwaitingPayment => $rank % 3,
		};
		$at = $this->startedAt->copy()->subDays($daysAgo)->setTime(10 + ($index % 10), ($index * 7) % 60);
		if ($at->greaterThan($this->startedAt->copy()->subHours(6))) {
			$at = $this->startedAt->copy()->subHours(6 + $index % 5);
		}

		$this->at($at);
		$order = Order::factory()->awaitingPayment()->for($customer, 'customer')->create([
			'game' => $purchase['game'],
			'service' => $purchase['service'],
			'title' => $purchase['title'],
			'server' => $purchase['server'],
			'account_name' => $customer->username.'_main',
			'account_login' => $customer->username.'.riot',
			'account_password' => 'demo-'.$customer->username,
			'price_cents' => $purchase['cents'],
			'quote' => [
				'game' => $purchase['game'],
				'service' => $purchase['service'],
				'title' => $purchase['title'],
				'lines' => [['label' => $purchase['title'], 'cents' => $purchase['cents']]],
				'options' => $purchase['options'],
				'total_cents' => $purchase['cents'],
			],
			'comment' => $index % 4 === 0 ? 'Please play only in the evenings, CET.' : null,
		]);
		$order->recordEvent(OrderEventType::Created, $customer, null, OrderStatus::AwaitingPayment);

		if ($status === OrderStatus::AwaitingPayment) {
			return;
		}

		$this->at($at->addMinutes(3));
		$order->forceFill(['status' => OrderStatus::Pending, 'paid_at' => now()])->save();
		$order->recordEvent(OrderEventType::PaymentSucceeded, null, OrderStatus::AwaitingPayment, OrderStatus::Pending);

		if ($status === OrderStatus::Pending) {
			// One pending order was dropped by a booster before, so its timeline shows a release
			if ($index === 3) {
				$this->at($at->addHours(2));
				app(ClaimOrder::class)->handle($this->boosters[1], $order);
				$this->at($at->addHours(5));
				app(ReleaseOrder::class)->handle($this->boosters[1], $order->fresh());
			}

			return;
		}

		$this->at($at->addHours(1 + $index % 4));
		// Most orders are claimed from the jobs board; staff hand out every fifth one
		$index % 5 === 0
			? app(AssignOrder::class)->handle($index % 10 === 0 ? $this->admin : $this->moderator, $order, $booster)
			: app(ClaimOrder::class)->handle($booster, $order);
		$this->seedChat($order->fresh(), $at);

		if ($status === OrderStatus::Paused) {
			$this->at($at->addHours(8));
			app(PauseOrder::class)->handle($customer, $order->fresh());

			return;
		}

		if ($status === OrderStatus::InProgress) {
			return;
		}

		$this->at($at->addDays(2));
		app(CompleteOrder::class)->handle($booster, $order->fresh());

		if ($status === OrderStatus::PaidOut) {
			$this->at($at->addDays(7));
			app(MarkOrderPaidOut::class)->handle($this->admin, $order->fresh());
		}
	}

	private function seedChat(Order $order, Carbon $claimedAt): void
	{
		foreach (self::CHAT as $minutes => [$from, $body]) {
			Message::factory()->for($order)->read()->create([
				'sender_id' => $from === 'customer' ? $order->customer_id : $order->booster_id,
				'body' => $body,
				'created_at' => $claimedAt->copy()->addMinutes(10 + $minutes * 95),
			]);
		}
	}

	private function seedFines(): void
	{
		Fine::factory()->for($this->boosters[0], 'booster')->for($this->admin, 'issuer')->create([
			'amount_cents' => 500,
			'reason' => 'Started an order a day later than agreed',
		]);
		Fine::factory()->for($this->boosters[1], 'booster')->for($this->admin, 'issuer')->create([
			'amount_cents' => 1000,
			'reason' => 'No message to the customer for 48 hours',
		]);
	}

	/**
	 * Freeze the clock for the next action, never later than when the seeder started.
	 */
	private function at(Carbon $time): void
	{
		Carbon::setTestNow($time->greaterThan($this->startedAt) ? $this->startedAt->copy() : $time->copy());
	}
}
