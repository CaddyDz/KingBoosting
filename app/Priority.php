<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Priority
 *
 * @property int $id
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Priority whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Priority extends Model
{
    //
}
