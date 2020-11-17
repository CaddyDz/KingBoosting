<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\AccountSelling
 *
 * @property int $id
 * @property string $server
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling whereServer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AccountSelling whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AccountSelling extends Model
{
	use SoftDeletes;
}
