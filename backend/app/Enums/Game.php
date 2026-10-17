<?php

declare(strict_types=1);

namespace App\Enums;

enum Game: string
{
	case LeagueOfLegends = 'lol';
	case Valorant = 'valorant';
	case WildRift = 'wildrift';

	public function label(): string
	{
		return match ($this) {
			self::LeagueOfLegends => 'League of Legends',
			self::Valorant => 'Valorant',
			self::WildRift => 'Wild Rift',
		};
	}
}
