<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Book;
use App\Models\UserInfo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains('id', $role->id);
    }

    public function permissions()
    {
        return $this->roles->flatMap(function ($role) {
            return $role->permissions;
        })->unique('id');
    }

    public function hasPermissionTo($permission)
    {
        return $this->permissions()->contains('name', $permission);
    }

    public function manageUsers()
    {
        $users = User::with('roles.permissions')->paginate(10); // Adjust the number as needed
        $roles = Role::all();
        $permissions = Permission::all();

        return view('manageUsers', compact('users', 'roles', 'permissions'));
    }


    public function bookEntry()
    {
        return $this->hasMany(Book::class, 'user_id', 'id');
    }

    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }
}
