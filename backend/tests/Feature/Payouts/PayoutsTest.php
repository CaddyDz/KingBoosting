<?php

declare(strict_types=1);

use App\Enums\OrderStatus;
use App\Notifications\PayoutSettled;
use Illuminate\Support\Facades\Notification;
use App\Models\{Fine, Order, Payout, User};

/**
 * A booster with three completed, unpaid orders and two unpaid fines, plus things that must not count.
 */
function boosterWithEarnings(): User
{
	$booster = User::factory()->booster()->create();
	Order::factory()->completed($booster)->create(['price_cents' => 10000, 'booster_share_pct' => 50]); // 50.00
	Order::factory()->completed($booster)->create(['price_cents' => 2425, 'booster_share_pct' => 50]);  // 12.125 → 12.13
	Order::factory()->completed($booster)->create(['price_cents' => 3000, 'booster_share_pct' => 40]);  // 12.00
	Fine::factory()->for($booster, 'booster')->create(['amount_cents' => 500]);
	Fine::factory()->for($booster, 'booster')->create(['amount_cents' => 300]);

	// Not owed: already paid out, still in progress, a settled fine, another booster's order
	$earlier = Payout::factory()->for($booster, 'booster')->create();
	Order::factory()->paidOut($booster)->create(['payout_id' => $earlier->id]);
	Order::factory()->inProgress($booster)->create();
	Fine::factory()->for($booster, 'booster')->create(['amount_cents' => 900, 'payout_id' => $earlier->id]);
	Order::factory()->completed()->create();

	return $booster;
}

it('totals what a booster is owed across orders and fines', function (): void {
	$booster = boosterWithEarnings();

	$this->actingAs($booster)->getJson('/api/earnings')
		->assertOk()
		->assertJsonPath('data.summary', [
			'orders_count' => 3,
			'share_cents' => 5000 + 1213 + 1200,
			'fines_cents' => 800,
			'owed_cents' => 7413 - 800,
		])
		->assertJsonCount(3, 'data.orders')
		->assertJsonCount(3, 'data.fines')
		->assertJsonCount(1, 'data.payouts');
});

it('keeps the earnings page to boosters', function (): void {
	$this->actingAs(User::factory()->customer()->create())->getJson('/api/earnings')->assertForbidden();
});

it('lists the boosters waiting for a payout', function (): void {
	$booster = boosterWithEarnings();

	$this->actingAs(User::factory()->admin()->create())->getJson('/api/payouts/owed')
		->assertOk()
		->assertJsonCount(2, 'data')
		->assertJsonFragment(['booster' => ['id' => $booster->id, 'username' => $booster->username], 'owed_cents' => 6613]);
});

it('pays boosters out in a batch', function (): void {
	Notification::fake();
	$booster = boosterWithEarnings();
	$other = User::factory()->booster()->create();
	Order::factory()->completed($other)->create(['price_cents' => 2000, 'booster_share_pct' => 50]);

	$response = $this->actingAs(User::factory()->admin()->create())
		->postJson('/api/payouts', ['booster_ids' => [$booster->id, $other->id]])
		->assertCreated()
		->assertJsonCount(2, 'data')
		->assertJsonPath('skipped', []);

	$payout = Payout::query()->where('booster_id', $booster->id)->latest('id')->first();
	expect($payout)
		->gross_cents->toBe(7413)
		->fines_cents->toBe(800)
		->amount_cents->toBe(6613)
		->orders_count->toBe(3);
	expect($booster->jobs()->where('status', OrderStatus::Completed)->count())->toBe(0)
		->and($payout->orders()->pluck('status')->unique()->all())->toBe([OrderStatus::PaidOut])
		->and($payout->fines()->count())->toBe(2);
	Notification::assertSentTo($booster, PayoutSettled::class, fn (PayoutSettled $notification): bool => $notification->body() === 'You\'ve been paid 66.13 € for 3 orders after 8.00 € in fines.');

	// Nothing left to pay the second time
	$this->postJson('/api/payouts', ['booster_ids' => [$booster->id]])
		->assertConflict()
		->assertJsonPath('skipped.0.booster_id', $booster->id);
});

it('waits when the fines are more than the earnings', function (): void {
	$booster = User::factory()->booster()->create();
	Order::factory()->completed($booster)->create(['price_cents' => 1000, 'booster_share_pct' => 50]);
	Fine::factory()->for($booster, 'booster')->create(['amount_cents' => 800]);

	$this->actingAs(User::factory()->admin()->create())
		->postJson('/api/payouts', ['booster_ids' => [$booster->id]])
		->assertConflict();

	expect(Payout::query()->count())->toBe(0)
		->and($booster->jobs()->sole()->status)->toBe(OrderStatus::Completed);
});

it('pays out one order on its own and leaves the fines for the next payout', function (): void {
	Notification::fake();
	$booster = boosterWithEarnings();
	$order = $booster->jobs()->where('status', OrderStatus::Completed)->firstOrFail();

	$this->actingAs(User::factory()->admin()->create())
		->postJson("/api/orders/{$order->id}/mark-paid-out")
		->assertOk()
		->assertJsonPath('data.status.value', 'paid_out');

	expect($order->fresh()->payout)
		->orders_count->toBe(1)
		->fines_cents->toBe(0)
		->and($booster->fines()->whereNull('payout_id')->count())->toBe(2);
});

it('keeps payouts to admins', function (): void {
	$booster = boosterWithEarnings();

	$this->actingAs(User::factory()->moderator()->create())
		->postJson('/api/payouts', ['booster_ids' => [$booster->id]])
		->assertForbidden();
	$this->getJson('/api/payouts')->assertForbidden();
});

it('lets admins issue, change and withdraw fines until they are deducted', function (): void {
	$booster = User::factory()->booster()->create();
	$this->actingAs($admin = User::factory()->admin()->create());

	$id = $this->postJson('/api/fines', ['booster_id' => $booster->id, 'amount_cents' => 500, 'reason' => 'Late start'])
		->assertCreated()
		->assertJsonPath('data.settled', false)
		->json('data.id');
	expect(Fine::query()->findOrFail($id)->issued_by)->toBe($admin->id);

	$this->putJson("/api/fines/{$id}", ['amount_cents' => 750, 'reason' => 'Late start, twice'])->assertOk()->assertJsonPath('data.amount_cents', 750);
	$this->getJson("/api/fines?booster_id={$booster->id}")->assertOk()->assertJsonCount(1, 'data');

	Fine::query()->whereKey($id)->update(['payout_id' => Payout::factory()->for($booster, 'booster')->create()->id]);
	$this->putJson("/api/fines/{$id}", ['amount_cents' => 100, 'reason' => 'x'])->assertConflict();
	$this->deleteJson("/api/fines/{$id}")->assertConflict();
});

it('only fines boosters, and only admins fine', function (): void {
	$this->actingAs(User::factory()->admin()->create())
		->postJson('/api/fines', ['booster_id' => User::factory()->customer()->create()->id, 'amount_cents' => 500, 'reason' => 'x'])
		->assertUnprocessable();

	$this->actingAs(User::factory()->moderator()->create())
		->postJson('/api/fines', ['booster_id' => User::factory()->booster()->create()->id, 'amount_cents' => 500, 'reason' => 'x'])
		->assertForbidden();
});
