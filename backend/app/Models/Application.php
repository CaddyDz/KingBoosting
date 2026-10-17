<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\ApplicationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * A booster application sent from the storefront's /apply page.
 */
#[Fillable([
	'in_game_name',
	'email',
	'country',
	'discord_skype',
	'rank',
	'languages',
	'games',
	'servers',
	'hours',
	'motivation',
])]
class Application extends Model
{
	/** @use HasFactory<ApplicationFactory> */
	use HasFactory;

	/**
	 * Get the attributes that should be cast.
	 *
	 * @return array<string, string>
	 */
	protected function casts(): array
	{
		return [
			'reviewed_at' => 'datetime',
		];
	}
}
