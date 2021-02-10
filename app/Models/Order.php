<?php

declare(strict_types=1);

namespace App\Models;

use Laravel\Nova\Actions\Actionable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
	use Actionable, HasFactory, LogsActivity;

	protected $with = ['booster', 'client', 'chat'];

	protected static $logUnguarded = true;

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
				return 'circular-graph';
			case 'progress':
				return 'controller-play';
			case 'paused':
				return 'controller-paus';
			case 'completed':
				return 'check';
			case 'suspended':
				return 'circle-with-cross';
			default:
				return 'controller-play';
		}
	}

	public function chat()
	{
		return $this->hasOne(Chat::class);
	}
}
