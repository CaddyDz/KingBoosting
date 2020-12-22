<?php

declare(strict_types=1);

namespace App\Http\Controllers\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Http\Controllers\LoginController as Controller;

class LoginController extends Controller
{
	/**
	 * Get the post register / login redirect path.
	 *
	 * @return string
	 */
	public function redirectPath(): string
	{
		return '/resources/orders';
	}

	protected function sendLoginResponse(Request $request)
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		$redirectPath = $this->redirectPath();
		redirect()->setIntendedUrl($redirectPath);

		return redirect()->intended($redirectPath);
	}
}
