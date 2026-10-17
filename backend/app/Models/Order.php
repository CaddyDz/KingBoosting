<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\{Game, OrderStatus};
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\{Fillable, Hidden};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * Status, customer and booster aren't fillable: only the order actions change them.
 *
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
	use HasFactory;

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
	 * What the booster earns for this order, rounded half up to the cent.
	 */
	public function boosterShareCents(): int
	{
		return (int) round($this->price_cents * $this->booster_share_pct / 100);
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
