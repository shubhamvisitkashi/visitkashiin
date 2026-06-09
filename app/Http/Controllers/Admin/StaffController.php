<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Admin;
use App\Models\UserTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:staff-list|staff-create|staff-edit|staff-delete', ['only' => ['index','store']]);
        $this->middleware('permission:staff-create', ['only' => ['create','store']]);
        $this->middleware('permission:staff-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:staff-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $list = Admin::where('id', '!=', 1)->paginate(20);

        // Load current month's targets keyed by user_id for quick access in view
        $currentTargets = UserTarget::where('month', now()->month)
            ->where('year', now()->year)
            ->get()
            ->keyBy('user_id');

        return view('admin.staff.index', compact('list', 'currentTargets'), ['page_title' => 'All Staffs']);
    }

    public function create()
    {
        $roles = Role::where('id', '!=', 1)->pluck('name','name')->all();
        return view('admin.staff.create', compact('roles'), ['page_title' => 'Add Staff']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'             => 'required',
            'email'            => 'required|email|unique:admins,email',
            'password'         => 'required|same:confirm-password|min:8',
            'roles'            => 'required',
            'avatar'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $staff = new Admin;
        $staff->name     = $request->name;
        $staff->email    = $request->email;
        $staff->password = bcrypt($request->password);
        $staff->plain_password = $request->password;

        if ($request->hasFile('avatar')) {
            $file     = $request->file('avatar');
            $filename = 'avatar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $staff->avatar = $filename;
        }

        $staff->save();
        $staff->assignRole($request->input('roles'));

        return redirect()->route('staffs.index')->with('success', 'Staff Added Successfully');
    }

    public function show($id) {}

    public function edit($id)
    {
        $data     = Admin::find($id);
        $roles    = Role::where('id', '!=', 1)->pluck('name','name')->all();
        $userRole = $data->roles->pluck('name','name')->all();
        return view('admin.staff.edit', compact('data','roles','userRole'), ['page_title' => 'Update Staff']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'     => 'required',
            'email'    => 'required|email|unique:admins,email,'.$id,
            'password' => 'nullable|min:8|same:confirm-password',
            'roles'    => 'required',
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $staff        = Admin::find($id);
        $staff->name  = $request->name;
        $staff->email = $request->email;

        if (!empty($request->password)) {
            $staff->password = bcrypt($request->password);
        $staff->plain_password = $request->password;
        }

        if ($request->hasFile('avatar')) {
            if ($staff->avatar && file_exists(public_path('uploads/avatars/'.$staff->avatar))) {
                @unlink(public_path('uploads/avatars/'.$staff->avatar));
            }
            $file     = $request->file('avatar');
            $filename = 'avatar_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $staff->avatar = $filename;
        }

        $staff->save();

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $staff->assignRole($request->input('roles'));

        return redirect()->route('staffs.index')->with('success', 'Staff updated successfully');
    }

    public function destroy($id)
    {
        $staff = Admin::find($id);
        if ($staff && $staff->avatar && file_exists(public_path('uploads/avatars/'.$staff->avatar))) {
            @unlink(public_path('uploads/avatars/'.$staff->avatar));
        }
        $staff->delete();
        return redirect()->route('staffs.index')->with('success', 'Staff deleted successfully');
    }
}
