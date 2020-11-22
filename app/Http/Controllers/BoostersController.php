<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class BoostersController extends Controller
{
    public function getBoostersNames()
	{
		// Returns only users with the role 'Booster'
		$boostersNames = User::role('Booster')->select('username')->pluck('username');
		return $boostersNames;
	}
}
