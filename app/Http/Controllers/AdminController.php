<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function manageUsers()
    {
        $users = User::with('roles')->select('id', 'name', 'email')->get();
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.manageUsers', compact('users', 'roles', 'permissions'));
    }
    public function updateUserRole(Request $request, User $user)
    {
        try {
            $role = Role::findOrFail($request->role);
            $user->roles()->sync([$role->id]);

            return back()->with('success', 'User role updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user role: ' . $e->getMessage());
            return back()->with('error', 'Error updating user role. Please try again.');
        }
    }

    public function createRole(Request $request)
    {
        $role = Role::create(['name' => $request->role_name]);
        $role->permissions()->attach($request->permissions);
        return redirect()->route('usertool')->with('success', 'Role created successfully.');
    }
}
