<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WinBoosting
 *
 * @property int $id
 * @property int|null $type_id
 * @property int $tier_id
 * @property int $wins
 * @property string $eta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Tier $tier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereEta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereTierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WinBoosting whereWins($value)
 * @mixin \Eloquent
 */
class WinBoosting extends Model
{
    public function tier()
    {
        return $this->belongsTo(Tier::class);
    }
}
