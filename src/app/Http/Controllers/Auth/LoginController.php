<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
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

    // new code
    public const SANCTUM_BEARER_TOKEN_KEY = 'bearer';

    /**
     * The user has been authenticated
     * Override authenticated from: vendor/laravel/ui/auth-backend/AuthenticatesUsers.php
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
                        $user->tokens()->delete();
        $tokenDefault = $user->createToken(self::SANCTUM_BEARER_TOKEN_KEY)->plainTextToken;

        if ($request->hasSession())
        {
            $request->session()->put('auth.token_bearer', $tokenDefault);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request))
        {
            return $response;
        }

        return $request->wantsJson() ? new JsonResponse([], 204) : redirect('/');
    }
    /**
     * The user has logged out of the application
     * Override authenticated from: vendor/laravel/ui/auth-backend/AuthenticatesUsers.php
     *
     * $request->user() === null
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {

    }
    // new code

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
}
