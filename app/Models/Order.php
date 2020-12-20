<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
	use HasFactory;

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

	public function getIconAttribute(): string
	{
		switch ($this->status) {
			case 'pending':
				return 'entypo:circular-graph';
			case 'progress':
				return 'entypo:controller-play';
			case 'paused':
				return 'entypo:controller-paus';
			case 'completed':
				return 'entypo:check';
			case 'suspended':
				return 'entypo:circle-with-cross';
			default:
				return 'entypo:controller-play';
		}
	}
}
