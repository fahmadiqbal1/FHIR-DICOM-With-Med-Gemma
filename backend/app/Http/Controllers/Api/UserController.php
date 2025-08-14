<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Get all users with their roles
     */
    public function index()
    {
        try {
            $users = User::with('roles')->orderBy('created_at', 'desc')->get();
            
            // Format the response to handle encrypted names and clean up the structure
            $formattedUsers = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name, // User model should handle decryption automatically
                    'email' => $user->email,
                    'roles' => $user->roles->map(function($role) {
                        return ['name' => $role->name];
                    }),
                    'role' => $user->roles->first()->name ?? 'No Role',
                    'revenue_share' => $user->revenue_share,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ];
            });
            
            return response()->json($formattedUsers);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:Admin,Doctor,Radiologist,Pharmacist,Lab Technician,Pathologist,Nurse,Receptionist,Patient']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign role if roles system is available
            if (class_exists('Spatie\Permission\Models\Role') && method_exists($user, 'assignRole')) {
                $user->assignRole($request->role);
                $user->load('roles'); // Reload to include roles
            }

            return response()->json([
                'message' => 'User created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name, // Will be decrypted automatically
                    'email' => $user->email,
                    'role' => $request->role,
                    'created_at' => $user->created_at,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific user
     */
    public function show(User $user)
    {
        try {
            $user->load('roles');
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', 'in:Admin,Doctor,Radiologist,Pharmacist,Lab Technician,Pathologist,Nurse,Receptionist,Patient']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Update role if roles system is available
            if (class_exists('Spatie\Permission\Models\Role') && method_exists($user, 'syncRoles')) {
                $user->syncRoles([$request->role]);
                $user->load('roles'); // Reload to include updated roles
            }

            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a user
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deletion of the current authenticated user
            if (Auth::check() && Auth::user()->id === $user->id) {
                return response()->json([
                    'message' => 'You cannot delete your own account'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available roles
     */
    public function roles()
    {
        try {
            $roles = [];
            
            if (class_exists('Spatie\Permission\Models\Role')) {
                $roleModel = app('Spatie\Permission\Models\Role');
                $roles = $roleModel::orderBy('name')->get();
            } else {
                // Fallback to predefined roles
                $roles = collect([
                    ['name' => 'Admin'],
                    ['name' => 'Doctor'],
                    ['name' => 'Radiologist'],
                    ['name' => 'Pharmacist'],
                    ['name' => 'Lab Technician'],
                    ['name' => 'Pathologist'],
                    ['name' => 'Nurse'],
                    ['name' => 'Receptionist'],
                    ['name' => 'Patient'],
                ]);
            }

            return response()->json($roles);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load roles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user earnings
     */
    public function earnings(User $user)
    {
        try {
            $earnings = \App\Models\DoctorEarning::where('doctor_id', $user->id)
                ->with(['patient:id,first_name,last_name'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($earning) {
                    return [
                        'id' => $earning->id,
                        'patient' => $earning->patient ? 
                            $earning->patient->first_name . ' ' . $earning->patient->last_name : 
                            'Unknown Patient',
                        'amount' => $earning->amount,
                        'service_type' => $earning->service_type,
                        'date' => $earning->created_at->format('Y-m-d'),
                    ];
                });
            
            return response()->json($earnings);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load user earnings',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
