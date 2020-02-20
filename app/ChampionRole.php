<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ChampionRole
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Champion[] $champions
 * @property-read int|null $champions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ChampionRole whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChampionRole extends Model
{
    /**
     * The champions that belong to the role.
     */
    public function champions()
    {
        return $this->belongsToMany(Champion::class);
    }
}
