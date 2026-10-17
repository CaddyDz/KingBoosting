<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\FineFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * An amount deducted from a booster's next payout.
 */
#[Fillable(['amount_cents', 'reason'])]
class Fine extends Model
{
	/** @use HasFactory<FineFactory> */
	use HasFactory;

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'amount_cents' => 'integer',
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
	public function issuer(): BelongsTo
	{
		return $this->belongsTo(User::class, 'issued_by');
	}
}
