<?php

declare(strict_types=1);

namespace App\Http\Controllers\Nova;

use Laravel\Nova\Http\Controllers\LoginController as Controller;

class LoginController extends Controller
{
	/**
	 * Show the application's login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showLoginForm()
	{
		if (app()->isLocal()) {
			return parent::showLoginForm();
		}
		return redirect()->away(config('sanctum.stateful')[0]);
	}
}
