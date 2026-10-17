<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\OrderEventRecorded;
use Illuminate\Database\Eloquent\{Builder, MassPrunable};
use Illuminate\Database\Eloquent\Attributes\Scope;
use App\Enums\{Game, OrderEventType, OrderStatus, Role};
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Status, customer and booster aren't fillable: only the order actions change them.
 *
 * @property OrderStatus $status
 * @property Game $game
 * @property array<string, mixed> $quote
 */
#[Fillable([
	'game',
	'service',
	'title',
	'server',
	'account_name',
	'account_login',
	'account_password',
	'quote',
	'price_cents',
	'booster_share_pct',
	'comment',
])]
#[Hidden(['account_login', 'account_password'])]
class Order extends Model
{
	/** @use HasFactory<OrderFactory> */
	use HasFactory, MassPrunable;

	/**
	 * @var array<string, mixed>
	 */
	protected $attributes = [
		'status' => 'awaiting_payment',
		'booster_share_pct' => 50,
	];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'status' => OrderStatus::class,
			'game' => Game::class,
			'quote' => 'array',
			'account_login' => 'encrypted',
			'account_password' => 'encrypted',
			'price_cents' => 'integer',
			'booster_share_pct' => 'integer',
			'paid_at' => 'datetime',
			'claimed_at' => 'datetime',
			'completed_at' => 'datetime',
			'paid_out_at' => 'datetime',
		];
	}

	/**
	 * Checkouts nobody paid for within a day (`php artisan model:prune`, scheduled daily).
	 *
	 * @return Builder<self>
	 */
	public function prunable(): Builder
	{
		return self::query()
			->where('status', OrderStatus::AwaitingPayment)
			->where('created_at', '<=', now()->subDay());
	}

	/**
	 * The orders the user may see: the same rule as OrderPolicy::view, as one grouped query.
	 *
	 * @param  Builder<self>  $query
	 */
	#[Scope]
	protected function visibleTo(Builder $query, User $user): void
	{
		match ($user->role) {
			Role::Admin, Role::Moderator => null,
			Role::Booster => $query->where(fn (Builder $query) => $query
				->where('status', OrderStatus::Pending)
				->orWhere(fn (Builder $query) => $query
					->where('booster_id', $user->getKey())
					->whereNotIn('status', [OrderStatus::AwaitingPayment, OrderStatus::Pending]))),
			Role::Customer => $query->where('customer_id', $user->getKey()),
		};
	}

	/**
	 * What the booster earns for this order, rounded half up to the cent.
	 */
	public function boosterShareCents(): int
	{
		return (int) round($this->price_cents * $this->booster_share_pct / 100);
	}

	/**
	 * Write a row to the order's timeline and let listeners know once the transaction commits.
	 *
	 * @param  array<string, mixed>  $meta
	 */
	public function recordEvent(OrderEventType $type, ?User $actor = null, ?OrderStatus $from = null, ?OrderStatus $to = null, array $meta = []): OrderEvent
	{
		$event = $this->events()->create([
			'actor_id' => $actor?->getKey(),
			'type' => $type,
			'from_status' => $from,
			'to_status' => $to,
			'meta' => $meta === [] ? null : $meta,
		]);

		OrderEventRecorded::dispatch($event);

		return $event;
	}

	/**
	 * @return BelongsTo<User, $this>
	 */
	public function customer(): BelongsTo
	{
		return $this->belongsTo(User::class, 'customer_id');
	}

	/**
	 * @return BelongsTo<User, $this>
	 */
	public function booster(): BelongsTo
	{
		return $this->belongsTo(User::class, 'booster_id');
	}

	/**
	 * @return BelongsTo<Payout, $this>
	 */
	public function payout(): BelongsTo
	{
		return $this->belongsTo(Payout::class);
	}

	/**
	 * @return HasMany<Message, $this>
	 */
	public function messages(): HasMany
	{
		return $this->hasMany(Message::class);
	}

	/**
	 * @return HasMany<OrderEvent, $this>
	 */
	public function events(): HasMany
	{
		return $this->hasMany(OrderEvent::class);
	}
}
