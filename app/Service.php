<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
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
}
