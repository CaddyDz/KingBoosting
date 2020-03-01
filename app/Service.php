<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * Get service type
     *
     * Retreive the related service type model
     *
     * For example a service could be a "Division Boosting" or "Wins Boosting"
     * We need that to determine second section in the service page
     *
     * @return App\ServiceType
     **/
    public function type()
    {
        return $this->belongsToMany(ServiceType::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
