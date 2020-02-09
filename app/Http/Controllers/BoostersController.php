<?php

namespace App\Http\Controllers;

use App\User;

class BoostersController extends Controller
{
    public function getBoostersNames()
    {
        $boostersNames = User::role('Booster')->select('name')->pluck('name'); // Returns only users with the role 'Booster'
        return $boostersNames;
    }
}
