<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\OrderDetails
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\OrderDetails whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderDetails extends Model
{
	use SoftDeletes;

	public function order()
	{
		return $this->belongsTo(Order::class);
	}
}
