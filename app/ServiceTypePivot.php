<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceTypePivot extends Pivot
{
    protected $table = 'service_service_type';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
