<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Database\Factories\PayoutFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

/**
 * A payment to a booster: the shares of the completed orders it covers, minus the fines it settles.
 *
 * @property Carbon $paid_at
 */
class Payout extends Model
{
	/** @use HasFactory<PayoutFactory> */
	use HasFactory;

	/**
	 * @var list<string>
	 */
	protected $guarded = ['id'];

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'gross_cents' => 'integer',
			'fines_cents' => 'integer',
			'amount_cents' => 'integer',
			'orders_count' => 'integer',
			'paid_at' => 'datetime',
		];
	}

	/**
	 * @return BelongsTo<User, $this>
	 */
	public function booster(): BelongsTo
	{
		return $this->belongsTo(User::class, 'booster_id');
	}

	/**
	 * @return BelongsTo<User, $this>
	 */
	public function payer(): BelongsTo
	{
		return $this->belongsTo(User::class, 'paid_by');
	}

	/**
	 * @return HasMany<Order, $this>
	 */
	public function orders(): HasMany
	{
		return $this->hasMany(Order::class);
	}

	/**
	 * @return HasMany<Fine, $this>
	 */
	public function fines(): HasMany
	{
		return $this->hasMany(Fine::class);
	}
}
