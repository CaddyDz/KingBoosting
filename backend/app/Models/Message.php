<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Support\Carbon;
use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * A chat message on an order. There's no recipient: everyone who may chat on the order sees it.
 *
 * @property Carbon|null $read_at
 */
#[Fillable(['body'])]
class Message extends Model
{
	/** @use HasFactory<MessageFactory> */
	use HasFactory;

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'read_at' => 'datetime',
		];
	}

	/**
	 * System messages are posted by order actions and have no sender.
	 */
	public function isSystem(): bool
	{
		return $this->sender_id === null;
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
	public function sender(): BelongsTo
	{
		return $this->belongsTo(User::class, 'sender_id');
	}
}
