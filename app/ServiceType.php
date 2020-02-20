<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ServiceType
 *
 * @property int $id
 * @property string $type
 * @property string $icon
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Service[] $services
 * @property-read int|null $services_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServiceType extends Model
{
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
