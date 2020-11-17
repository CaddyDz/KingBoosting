<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use App\Http\Requests\SetPasswordRequest;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Email Verification Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling email verification for any
	| user that recently registered with the application. Emails may also
	| be re-sent if the user didn't receive the original email message.
	|
	*/

	use VerifiesEmails;

	/**
	 * Where to redirect users after verification.
	 *
	 * @var string
	 */
	protected $redirectTo = '/set-password';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:api');
		$this->middleware('signed')->only('verify');
		$this->middleware('throttle:6,1')->only('verify', 'resend');
	}

	/**
	 * Show the email verification notice.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request)
	{
		return $request->user()->hasVerifiedEmail()
			? response([
				'status' => 'already verified'
			])
			: response([
				'status' => 'Please verify your email'
			]);
	}

	public function showPasswordSettingForm()
	{
		return view('auth.passwords.setPassword');
	}

	public function setPassword(SetPasswordRequest $request)
	{
		auth()->user()->password = bcrypt($request->password);
		auth()->user()->save();
		return response([
			'status' => __('success'),
			'message' => __('Your password has been successfully set'),
		], 201);
	}

	/**
	 * Mark the authenticated user's email address as verified.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 */
	public function verify(Request $request)
	{
		if ($request->route('id') != $request->user()->getKey()) {
			return response(['status' => 'Forbidden'], 403);
		}

		if ($request->user()->hasVerifiedEmail()) {
			return response(['status' => 'Already verified'], 401);
		}

		if ($request->user()->markEmailAsVerified()) {
			event(new Verified($request->user()));
		}

		return response(['status' => 'Verified']);
	}
}
