<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Tier
 *
 * @property int $id
 * @property string $name
 * @property string $box_shadow
 * @property string|null $image
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Division[] $divisions
 * @property-read int|null $divisions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\WinBoosting[] $wins
 * @property-read int|null $wins_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier whereBoxShadow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tier extends Model
{
    use SoftDeletes;

    public function divisions()
    {
        return $this->hasMany(Division::class);
    }

    public function wins()
    {
        return $this->hasMany(WinBoosting::class);
    }
}
