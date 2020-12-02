<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;

class BoostersController extends Controller
{
	public function getBoostersNames()
	{
		// Returns only users with the role 'Booster'
		$boostersNames = User::role('Booster')->select('username')->pluck('username');
		return $boostersNames;
	}
}
