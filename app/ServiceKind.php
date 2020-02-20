<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ServiceKind
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Service[] $services
 * @property-read int|null $services_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ServiceKind whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServiceKind extends Model
{
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
