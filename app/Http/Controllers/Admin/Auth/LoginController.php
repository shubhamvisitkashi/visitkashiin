<?php

namespace App\Http\Controllers\Admin\Auth;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        return view('admin.auth.login', ['page_title' => 'Admin Login']);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required|min:8',
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "Too many login attempts. Please try again in {$seconds} seconds."]);
        }

        if (Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        RateLimiter::hit($throttleKey, 60);

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
