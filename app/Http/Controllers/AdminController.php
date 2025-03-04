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
        $users = User::with('roles.permissions')->paginate(10); // Add pagination
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

    public function updateUserPermissions(Request $request, User $user)
    {
        try {
            $role = $user->roles()->first(); // Assuming a user has a single role for simplicity
            if ($role) {
                $role->permissions()->sync($request->permissions);
            }
            return back()->with('success', 'User permissions updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating user permissions: ' . $e->getMessage());
            return back()->with('error', 'Error updating user permissions. Please try again.');
        }
    }

    public function deleteUser(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Error deleting user. Please try again.');
        }
    }


    // public function updateUserRole(Request $request, User $user)
    // {
    //     $role = Role::find($request->role_id);
    //     if ($role) {
    //         $user->roles()->sync([$role->id]);
    //         return back()->with('success', 'User role updated successfully.');
    //     }
    //     return back()->with('error', 'Role not found.');
    // }

    // public function createRole(Request $request)
    // {
    //     $role = Role::create(['name' => $request->role_name]);
    //     $role->permissions()->attach($request->permissions);
    //     return redirect()->route('usertool')->with('success', 'Role created successfully.');
    // }
    public function createRole(Request $request)
    {
        // Validate
        $request->validate([
            'role_name' => 'required|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id', // Each item in the permissions array must be a valid permission ID
        ]);

        try {
            $role = Role::create(['name' => $request->role_name]);
            // Attach the selected permissions sa role
            if ($request->has('permissions')) {
                $role->permissions()->attach($request->permissions);
            }

            return redirect()->route('usertool')->with('success', 'Role created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            return redirect()->route('usertool')->with('error', 'Error creating role. Please try again.');
        }
    }
}
