<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\OrderEventFactory;
use App\Enums\{OrderEventType, OrderStatus};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * One row per thing that happened to an order. Rows are written by the order actions and never updated.
 *
 * @property OrderEventType $type
 * @property OrderStatus|null $from_status
 * @property OrderStatus|null $to_status
 * @property array<string, mixed>|null $meta
 */
class OrderEvent extends Model
{
	/** @use HasFactory<OrderEventFactory> */
	use HasFactory;

	public const UPDATED_AT = null;

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
			'type' => OrderEventType::class,
			'from_status' => OrderStatus::class,
			'to_status' => OrderStatus::class,
			'meta' => 'array',
			'created_at' => 'datetime',
		];
	}

	/**
	 * @return BelongsTo<Order, $this>
	 */
	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}

	/**
	 * @return BelongsTo<User, $this>
	 */
	public function actor(): BelongsTo
	{
		return $this->belongsTo(User::class, 'actor_id');
	}
}
