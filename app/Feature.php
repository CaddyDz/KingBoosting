<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Feature
 *
 * @property int $id
 * @property string $icon
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Feature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Feature extends Model
{
	use SoftDeletes;
}
