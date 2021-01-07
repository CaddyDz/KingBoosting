<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
	use HasFactory;

	public function sender()
	{
		return $this->belongsTo(User::class, 'sender_id');
	}

	public function recepient()
	{
		return $this->belongsTo(User::class, 'recepient_id');
	}

	public function chat()
	{
		return $this->belongsTo(Chat::class);
	}
}
