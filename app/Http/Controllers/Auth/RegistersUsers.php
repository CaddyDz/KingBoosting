<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterAccountRequest;

trait RegistersUsers
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\RegisterAccountRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterAccountRequest $request)
    {
        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['username'] =  $user->username;
        return response()->json(['success' => $success], 200);
    }
}
