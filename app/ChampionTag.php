<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
