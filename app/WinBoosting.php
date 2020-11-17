<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\WinBoosting
 *
 * @property int $id
 * @property int|null $type_id
 * @property int $tier_id
 * @property int $wins
 * @property string $eta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Tier $tier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereEta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereTierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereWins($value)
 * @mixin \Eloquent
 */
class WinBoosting extends Model
{
	use SoftDeletes;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['wins', 'eta'];

	/**
	 * Get the tier that owns the division.
	 */
	public function tier()
	{
		return $this->belongsTo(Tier::class);
	}
}
