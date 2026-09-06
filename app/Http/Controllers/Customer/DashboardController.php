<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('frontend.account.dashboard', [
            'customer' => Auth::guard('web')->user(),
        ]);
    }
}
