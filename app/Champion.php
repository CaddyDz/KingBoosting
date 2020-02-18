<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
