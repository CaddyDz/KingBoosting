<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Service
 *
 * @property int $id
 * @property int $kind_id
 * @property int|null $type_id
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $subtitle
 * @property string|null $status
 * @property string $slug
 * @property string $bg_img
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ServiceKind $kind
 * @property-read \App\ServiceType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereBgImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereKindId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Service whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Service extends Model
{
	use SoftDeletes;

	/**
	 * Get the route key for the model.
	 *
	 * @return string
	 */
	public function getRouteKeyName()
	{
		return 'slug';
	}

	/**
	 * Get service kind
	 *
	 * Retreive the related service kind model
	 *
	 * For example a service could be an "ELO Boosting" or "Account Selling"
	 * We need that text to show in the service page
	 *
	 * @return App\ServiceKind
	 **/
	public function kind()
	{
		return $this->belongsTo(ServiceKind::class);
	}

	/**
	 * Get service types
	 *
	 * Retreive the related service type models
	 *
	 * For example a service could be a "Division Boosting" or "Wins Boosting" or both
	 * We need that to determine second section in the service page
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 **/
	public function types(): BelongsToMany
	{
		return $this->belongsToMany(ServiceType::class)
			->using(ServiceTypePivot::class)
			->withPivot([
				'name',
			]);
	}
}
