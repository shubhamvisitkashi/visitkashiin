<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __invoke()
    {
        $staffList = \App\Models\Admin\Admin::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get(['id','name','email','avatar','plain_password']);

        return view('admin.change_password', [
            'page_title' => 'My Profile',
            'staffList'  => $staffList,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'avatar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'name'             => 'required|string|max:100',
            'password'         => 'nullable|min:8|confirmed',
            'password_confirmation' => 'nullable',
        ]);

        $user = auth()->user();
        $data = ['name' => $request->name];

        // Avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                $old = public_path('uploads/avatars/'.$user->avatar);
                if (file_exists($old)) @unlink($old);
            }
            $file     = $request->file('avatar');
            $filename = 'avatar_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = $filename;
        }

        // Password update (only if provided)
        if ($request->filled('password')) {
            $data['password']       = Hash::make($request->password);
            $data['plain_password'] = $request->password;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }

    public function updateCalendarPin(Request $request)
    {
        $request->validate([
            'calendar_pin' => 'required|digits:6',
        ]);

        \App\Models\WebsiteSetup::updateOrCreate(
            ['name' => 'calendar_pin'],
            ['value' => $request->calendar_pin]
        );

        // Always return JSON (AJAX from modal sends Accept: application/json)
        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'pin'     => $request->calendar_pin,
                'message' => 'PIN updated to ' . $request->calendar_pin,
            ]);
        }

        return back()->with('cal_pin_success', 'Calendar PIN updated to ' . $request->calendar_pin);
    }

    public function resetStaffPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:6',
        ]);

        $staff = \App\Models\Admin\Admin::findOrFail($id);
        $staff->update([
            'password'       => Hash::make($request->new_password),
            'plain_password' => $request->new_password,
        ]);

        return back()->with('success', 'Password updated for ' . $staff->name);
    }
}
