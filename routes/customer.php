<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Customer\DashboardController;

// ── Customer auth + account area. Registered here, before web.php's
//    catch-all /{slug} and /{category_slug}/{sub_category_slug} routes,
//    so /account/* is never swallowed by them. ──
Route::prefix('account')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('customer.login');
    Route::post('login', [LoginController::class, 'login'])->name('customer.login.submit');
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('customer.register');
    Route::post('register', [RegisterController::class, 'register'])->name('customer.register.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('customer.logout');

    Route::get('auth/google', [GoogleAuthController::class, 'redirect'])->name('customer.google.redirect');
    Route::get('auth/google/callback', [GoogleAuthController::class, 'callback'])->name('customer.google.callback');

    Route::middleware(['auth.customer', 'customer.active'])->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('customer.dashboard');
    });
});
