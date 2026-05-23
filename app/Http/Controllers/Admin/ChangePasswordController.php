<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{

    public function __invoke() {
        return view('admin.change_password', ['page_title' => 'Change Password']);
    }

    public function changePassword(Request $request) {
        $request->validate([
            'password' => 'required|min:8',
        ]);

        $user = auth()->user();
        $user->password = \Hash::make($request->password);
        $user->save();

        auth()->logout();

        return redirect()->route('admin.login')->with('success', 'Password changed successfully');
    }

}
