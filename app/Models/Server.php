<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Server extends Model
{
	use HasFactory;

	public function boosters()
	{
		return $this->belongsToMany(User::class, 'booster_server', 'server_id', 'booster_id');
	}
}
