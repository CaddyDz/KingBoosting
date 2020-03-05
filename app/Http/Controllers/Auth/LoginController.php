<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /** @var Response $api_response Response returned from oauth */
    public $api_response = null;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function __construct(Request $request)
    {
        auth()->setDefaultDriver('api');
        $request->request->add([
            'username' => $request->email,
            'grant_type' => 'password',
            'client_id' => '2',
            'client_secret' => config('passport.client_secret'),
            'scope' => '*',
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validation = $this->validateLogin($request);
        if ($validation->fails()) {
            return response(['error' => $validation->errors()], 401);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        return Validator::make($request->all(), [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        // forward the request to the oauth token request endpoint
        $res = Route::dispatch(request()->create('oauth/token', 'POST', $this->credentials($request)));
        // Set api response for successful login
        $this->api_response = json_decode($res->getContent());
        // Return true or false based on response status code
        return $res->getStatusCode() === 200 ? true : false;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('username', 'password', 'grant_type', 'client_id', 'client_secret', 'scope');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return response()->json(['success' => $this->api_response], 200);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return response([$this->username() => trans('auth.failed')], 401);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        return $this->authenticated($request, $this->guard()->user());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // The guard must become auth:api somehow
        $request->user()->token()->revoke();
        return $this->loggedOut($request);
    }

    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
            $email = $user->getEmail();
            if (!$email) {
                return response([
                    'status' => 'failed',
                    'message' => 'No email address associated with this account',
                ]);
            }
            $user = User::where('email', $email)->first();
            if ($user === null) {
                return response([
                    'status' => 'failed',
                    'message' => 'No such user registered',
                ], 403);
            }
            auth()->login($user);
        } catch (Exception $ex) {
            logger()->error($ex->getMessage());
            return response([
                'status' => 'failed',
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Return 404 when accessing login page.
     *
     * Login modal is custom provided by the application
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return abort(404);
    }
}
