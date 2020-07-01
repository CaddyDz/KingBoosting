<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\DivisionBoosting
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DivisionBoosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DivisionBoosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DivisionBoosting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DivisionBoosting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DivisionBoosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DivisionBoosting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DivisionBoosting extends Model
{
    use SoftDeletes;
    public function type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function tier()
    {
        return $this->hasOne(ServiceType::class);
    }
}
