<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Every user has exactly one role (docs/adr/0002).
 */
enum Role: string
{
	case Admin = 'admin';
	case Moderator = 'moderator';
	case Booster = 'booster';
	case Customer = 'customer';

	public function label(): string
	{
		return match ($this) {
			self::Admin => 'Admin',
			self::Moderator => 'Moderator',
			self::Booster => 'Booster',
			self::Customer => 'Customer',
		};
	}

	/**
	 * Admins and moderators run the back office.
	 */
	public function isStaff(): bool
	{
		return $this === self::Admin || $this === self::Moderator;
	}
}
