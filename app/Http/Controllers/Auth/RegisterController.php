<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Mail\Registered as MailRegistered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;

class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = RouteServiceProvider::HOME;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data): ValidatorContract
	{
		return Validator::make($data, [
			'username' => ['required', 'string', 'max:255', 'unique:users'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'confirmed'],
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param array $data
	 * @return \App\Models\User
	 */
	protected function create(array $data): User
	{
		return User::create([
			'username' => $data['username'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'country' => $this->getClientCountry()
		]);
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
	 */
	public function register(Request $request)
	{
		$this->validator($request->all())->validate();

		$password = Str::random(10);

		$request->request->add(['password' => $password]);

		event(new Registered($user = $this->create($request->all())));

		return $this->registered($user, $password);
	}

	/**
	 * The user has been registered.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param mixed $user
	 * @return mixed
	 */
	protected function registered(User $user, string $password)
	{
		Mail::to($user)->send(new MailRegistered($user, $password));
		return response([
			'status' => __('Password has been sent to your email address'),
		], 201);
	}

	public function getClientCountry()
	{
		return isset($_SERVER["HTTP_CF_IPCOUNTRY"]) ? $_SERVER["HTTP_CF_IPCOUNTRY"] : 'US';
	}
}
