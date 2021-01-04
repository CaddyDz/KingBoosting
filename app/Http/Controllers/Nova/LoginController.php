<?php

declare(strict_types=1);

namespace App\Http\Controllers\Nova;

use Laravel\Nova\Nova;
use Laravel\Nova\Http\Controllers\LoginController as Controller;

class LoginController extends Controller
{
	// /**
	//  * Show the application's login form.
	//  *
	//  * @return \Illuminate\Http\Response
	//  */
	// public function showLoginForm()
	// {
	// 	return redirect()->away(config('sanctum.stateful')[0]);
	// }

	/**
	 * Get the post register / login redirect path.
	 *
	 * @return string
	 */
	public function redirectPath(): string
	{
		return url('/resources/orders');
	}
}
