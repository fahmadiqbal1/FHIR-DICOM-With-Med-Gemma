<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
// use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $rolesAvailable = Schema::hasTable('roles') && Schema::hasTable('model_has_roles') && class_exists('Spatie\Permission\Models\Role');
        $users = $rolesAvailable ? User::with('roles')->orderBy('id')->get() : User::orderBy('id')->get();
        $roles = $rolesAvailable ? app('Spatie\Permission\Models\Role')->orderBy('name')->get() : collect();

        return view('admin.users', [
            'users' => $users,
            'roles' => $roles,
            'rolesAvailable' => $rolesAvailable,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $rolesAvailable = Schema::hasTable('roles') && Schema::hasTable('model_has_roles') && class_exists('Spatie\Permission\Models\Role');

        $rules = [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
        ];
        if ($rolesAvailable) {
            $rules['role'] = ['required','string'];
        }

        $data = $request->validate($rules);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($rolesAvailable && $request->filled('role')) {
            $role = app('Spatie\Permission\Models\Role')->where('name', $request->input('role'))->first();
            if ($role) {
                $user->assignRole($role);
            }
        }

        return redirect()->route('admin.users.index')->with('status', 'User created successfully');
    }
}
