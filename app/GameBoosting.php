<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\GameBoosting
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameBoosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameBoosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameBoosting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameBoosting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameBoosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GameBoosting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GameBoosting extends Model
{
    use SoftDeletes;
}
