<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Champion
 *
 * @property int $id
 * @property string $name
 * @property string $identifier
 * @property string $image
 * @property int $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ChampionRole[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ChampionTag[] $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Champion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Champion extends Model
{
    /**
     * The roles that belong to the champion.
     */
    public function roles()
    {
        return $this->belongsToMany(ChampionRole::class);
    }

    /**
     * The tags that belong to the champion.
     */
    public function tags()
    {
        return $this->belongsToMany(ChampionTag::class);
    }
}
