<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{
	use HasFactory;

	public function boosters()
	{
		return $this->belongsToMany(User::class, 'users', 'game_id', 'booster_id');
	}
}
