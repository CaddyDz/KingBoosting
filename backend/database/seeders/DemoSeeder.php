<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Pricing\QuoteEngine;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Enums\{OrderEventType, OrderStatus};
use App\Models\{Application, Fine, Message, Order, User};
use App\Actions\Payouts\PayOutBooster;
use App\Actions\Orders\{AssignOrder, ClaimOrder, CompleteOrder, PauseOrder, ReleaseOrder};

/**
 * The public demo: named accounts for every role and about 40 orders in every status, game and service,
 * spread over the last eight weeks. Orders go through the real action classes, so their timelines exist.
 */
class DemoSeeder extends Seeder
{
	/**
	 * What customers bought: one order builder input per service, priced by the quote engine.
	 *
	 * @var list<array{service: string, server: string, input: array<string, mixed>}>
	 */
	private const PURCHASES = [
		['service' => 'division-boosting', 'server' => 'euw', 'input' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'III'], 'lp' => '21-40', 'mmr' => '15-17']],
		['service' => 'division-boosting', 'server' => 'eune', 'input' => ['current' => ['tier' => 'platinum', 'division' => 'II'], 'desired' => ['tier' => 'diamond', 'division' => 'IV'], 'lp' => '41-60', 'mmr' => '13-15', 'options' => ['priority']]],
		['service' => 'duoqueue-boosting', 'server' => 'euw', 'input' => ['current' => ['tier' => 'silver', 'division' => 'IV'], 'desired' => ['tier' => 'gold', 'division' => 'IV'], 'lp' => '0-20', 'mmr' => '21+', 'options' => ['coaching']]],
		['service' => 'placement-boosting', 'server' => 'na', 'input' => ['current' => ['tier' => 'gold', 'division' => 'II'], 'amount' => 6, 'options' => ['play-with-booster']]],
		['service' => 'ranked-wins-boosting', 'server' => 'euw', 'input' => ['current' => ['tier' => 'gold', 'division' => 'II'], 'amount' => 6, 'options' => ['play-with-booster']]],
		['service' => 'champion-mastery', 'server' => 'euw', 'input' => ['champion' => 'Ahri', 'level' => ['current' => 5, 'desired' => 7], 'tokens' => 5]],
		['service' => 'valorant-boosting', 'server' => 'eu', 'input' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'III']]],
		['service' => 'valorant-placement-boosting', 'server' => 'eu', 'input' => ['current' => ['tier' => 'platinum', 'division' => 'II'], 'amount' => 3, 'options' => ['streaming']]],
		['service' => 'unrated-matches', 'server' => 'na', 'input' => ['amount' => 15, 'options' => ['net-wins']]],
		['service' => 'wildrift-rank-boosting', 'server' => 'europe', 'input' => ['current' => ['tier' => 'gold', 'division' => 'IV'], 'desired' => ['tier' => 'platinum', 'division' => 'IV'], 'options' => ['priority']]],
		['service' => 'wildrift-placement-boosting', 'server' => 'europe', 'input' => ['current' => ['tier' => 'silver', 'division' => 'III'], 'amount' => 5, 'options' => ['priority']]],
		['service' => 'wildrift-ranked-wins-boosting', 'server' => 'americas', 'input' => ['current' => ['tier' => 'platinum', 'division' => 'IV'], 'amount' => 8]],
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
		// The seeded history shouldn't reach anyone: no emails, no live broadcasts, no queued jobs left behind
		config(['broadcasting.default' => 'null', 'queue.default' => 'sync', 'mail.default' => 'array']);

		$this->startedAt = now();
		$this->seedUsers();
		$this->seedOrders();
		$this->seedFines();
		Application::factory()->count(2)->create();
		Application::factory()->reviewed()->create();

		// Only the last two days' notifications are new, so the bell shows a few, not the whole history
		DB::table('notifications')->where('created_at', '<', $this->startedAt->copy()->subDays(2))->update(['read_at' => $this->startedAt]);
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

		$quote = app(QuoteEngine::class)->quote($purchase['service'], $purchase['input']);

		$this->at($at);
		$order = Order::factory()->awaitingPayment()->for($customer, 'customer')->create([
			'game' => $quote->service->game,
			'service' => $quote->service->key,
			'title' => $quote->title,
			'server' => $purchase['server'],
			'account_name' => $customer->username.'_main',
			'account_login' => $customer->username.'.riot',
			'account_password' => 'demo-'.$customer->username,
			'price_cents' => $quote->totalCents,
			'quote' => $quote->toArray(),
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
			app(PayOutBooster::class)->handle($this->admin, $booster, [$order->id]);
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
