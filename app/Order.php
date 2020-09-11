<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Order
 *
 * @property int $id
 * @property int $client_id
 * @property int $booster_id
 * @property string $status
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $booster
 * @property-read \App\User $client
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereBoosterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
	use SoftDeletes;
	public function client(): BelongsTo
	{
		return $this->belongsTo(User::class, 'client_id', 'id');
	}

	public function booster(): BelongsTo
	{
		return $this->belongsTo(User::class, 'booster_id');
	}

	public function getIconAttribute(): string
	{
		switch ($this->status) {
			case 'pending':
				return 'entypo:circular-graph';
			case 'progress':
				return 'entypo:controller-play';
			case 'paused':
				return 'entypo:controller-paus';
			case 'completed':
				return 'entypo:check';
			case 'suspended':
				return 'entypo:circle-with-cross';
			default:
				return 'entypo:controller-play';
		}
	}

	public function details(): HasOne
	{
		return $this->hasOne(OrderDetails::class);
	}

	public function getTypeAttribute(): string
	{
		if ($this->service->name === 'League Boosting') {
			return 'What the fuck';
		}
	}

	public function service(): BelongsTo
	{
		return $this->belongsTo(ServiceTypePivot::class, 'service_service_type_id', 'id');
	}
}
