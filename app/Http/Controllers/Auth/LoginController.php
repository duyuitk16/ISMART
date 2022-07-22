<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

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

    use AuthenticatesUsers {
        logout as performLogout;
    }
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
    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect()->route('login');
    }


    // Tùy chỉnh thời gian Cookie
    protected function changeExpireCookieRemember()
    {
        $rememberTokenExpireMinutes = 60 * 24;
        $rememberTokenName          = Auth::getRecallerName();
        $rememberCookie             = Auth::getCookieJar()->queued($rememberTokenName);
        if ($rememberCookie) {
            $cookieValue = $rememberCookie->getValue();
            Cookie::queue($rememberTokenName, $cookieValue, $rememberTokenExpireMinutes);
        }
    }
    protected function sendLoginResponse(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        $remember    = $request->remember ? true : false;
        if (Auth::attempt($credentials, $remember)) {
            $this->changeExpireCookieRemember();
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
