<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

class UserObserver
{
	/**
	 * Handle the User "creating" event.
	 *
	 * @param \App\Models\User $user
	 * @return void
	 */
	public function creating(User $user): void
	{
		$user->country = $this->getClientCountry();
	}

	public function getClientCountry(): string
	{
		return isset($_SERVER["HTTP_CF_IPCOUNTRY"]) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : 'US';
	}
}
