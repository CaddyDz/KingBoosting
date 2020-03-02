<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Division
 *
 * @property int $id
 * @property int $tier_id
 * @property string $image
 * @property float $price
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Tier $tier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereTierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Division whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Division extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'image'];

    /**
     * Get the tier that owns the division.
     */
    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }
}
