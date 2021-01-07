<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
	use HasFactory;

	protected $with = ['messages'];

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function messages()
	{
		return $this->hasMany(Message::class);
	}
}
