<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            if ($user->status === 'inactive') {
                return redirect()->route('customer.login')
                    ->withErrors(['email' => 'Your account has been disabled. Please contact support.']);
            }

            $user->google_id = $user->google_id ?: $googleUser->getId();
            $user->login_method = $user->login_method ?: 'google';
        } else {
            $user = new User();
            $user->name = $googleUser->getName() ?: $googleUser->getNickname();
            $user->email = $googleUser->getEmail();
            $user->google_id = $googleUser->getId();
            $user->login_method = 'google';
            $user->status = 'active';
            $user->email_verified_at = now();
        }

        $user->last_login_at = now();
        $user->save();

        Auth::guard('web')->login($user, true);
        $request->session()->regenerate();

        return redirect('/account/dashboard');
    }
}
