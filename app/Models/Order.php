<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
	use HasFactory;

	protected $guarded = [];

	protected $casts = [
		'options' => 'array',
	];

	public function booster()
	{
		return $this->belongsTo(User::class, 'booster_id');
	}

	public function client()
	{
		return $this->belongsTo(User::class, 'client_id');
	}
}
