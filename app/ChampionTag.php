<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ChampionTag
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Champion[] $champions
 * @property-read int|null $champions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionTag whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChampionTag extends Model
{
    /**
     * The champions that belong to the role.
     */
    public function champions()
    {
        return $this->belongsToMany(Champion::class);
    }
}
