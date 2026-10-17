<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\{Order, User};
use App\Enums\{Game, OrderStatus};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Orders default to a paid, unclaimed League of Legends division boost (`pending`).
 *
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
	private const DIVISIONS = ['Iron', 'Bronze', 'Silver', 'Gold', 'Platinum', 'Diamond'];

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$from = fake()->numberBetween(0, 4);
		$to = fake()->numberBetween($from + 1, 5);
		$title = self::DIVISIONS[$from].' IV → '.self::DIVISIONS[$to].' IV';
		$cents = fake()->numberBetween(20, 400) * 100 + fake()->randomElement([0, 25, 50, 75]);

		return [
			'customer_id' => User::factory(),
			'booster_id' => null,
			'status' => OrderStatus::Pending,
			'game' => Game::LeagueOfLegends,
			'service' => 'division-boosting',
			'title' => $title,
			'server' => fake()->randomElement(['euw', 'eune', 'na', 'oce', 'br']),
			'account_name' => Str::limit(fake()->userName(), 16, ''),
			'account_login' => fake()->userName(),
			'account_password' => fake()->password(10, 16),
			'quote' => [
				'service' => 'division-boosting',
				'title' => $title,
				'lines' => [['label' => $title, 'cents' => $cents]],
				'options' => [],
				'total_cents' => $cents,
			],
			'price_cents' => $cents,
			'booster_share_pct' => 50,
			'comment' => fake()->optional(0.3)->sentence(),
			'payment_intent_id' => 'pi_test_'.Str::random(24),
			'paid_at' => now()->subDays(3),
		];
	}

	/**
	 * Created at checkout, payment not confirmed yet.
	 */
	public function awaitingPayment(): static
	{
		return $this->state([
			'status' => OrderStatus::AwaitingPayment,
			'booster_id' => null,
			'paid_at' => null,
		]);
	}

	/**
	 * Paid and waiting for a booster.
	 */
	public function pending(): static
	{
		return $this->state([
			'status' => OrderStatus::Pending,
			'booster_id' => null,
			'claimed_at' => null,
		]);
	}

	/**
	 * Claimed by a booster, who is playing it now.
	 */
	public function inProgress(?User $booster = null): static
	{
		return $this->state(fn (array $attributes): array => [
			'status' => OrderStatus::InProgress,
			'booster_id' => $booster ?? User::factory()->booster(),
			'claimed_at' => now()->subDays(2),
		]);
	}

	public function paused(?User $booster = null): static
	{
		return $this->inProgress($booster)->state(['status' => OrderStatus::Paused]);
	}

	public function completed(?User $booster = null): static
	{
		return $this->inProgress($booster)->state([
			'status' => OrderStatus::Completed,
			'completed_at' => now()->subDay(),
		]);
	}

	/**
	 * Completed, and the booster's share has been paid out.
	 */
	public function paidOut(?User $booster = null): static
	{
		return $this->completed($booster)->state([
			'status' => OrderStatus::PaidOut,
			'paid_out_at' => now(),
		]);
	}
}
