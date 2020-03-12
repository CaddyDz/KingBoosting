<?php

namespace App\Http\Controllers;

use App\User;

class BoostersController extends Controller
{
    public function index()
    {
        return User::role('Booster')->get();
    }

    public function getBoostersNames()
    {
        // Returns only users with the role 'Booster'
        $boostersNames = User::role('Booster')->select('username')->pluck('username');
        return $boostersNames;
    }
}
