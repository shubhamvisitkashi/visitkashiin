<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Customer-facing login, using the default "web" guard (App\Models\User).
    | Admin login is entirely separate (Admin\Auth\LoginController, "admin"
    | guard) and is unaffected by this controller.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect customers after login.
     *
     * @var string
     */
    protected $redirectTo = '/account/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest.customer')->except('logout');
    }

    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }
}
