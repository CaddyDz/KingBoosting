<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Application;
use App\Http\Requests\ApplicationRequest;

class BoostersController extends Controller
{
	public function getBoostersNames()
	{
		// Returns only users with the role 'Booster'
		$boostersNames = User::role('Booster')->select('username')->pluck('username');
		return $boostersNames;
	}

	public function apply(ApplicationRequest $request)
	{
		$created = Application::create($request->all());
		if ($created) {
			return response([
				'status' => 'created',
				'message' => __('Your application has been sent'),
			], 201);
		}
		return response([
			'status' => 'failed',
			'message' => __('There has been an error, please try again later')
		]);
	}
}
