<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\AdminWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Create a new admin user and send welcome email
     */
    public function createAdmin(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create the admin user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign admin role if roles system is configured
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('admin');
            }

            // Send welcome email with credentials
            try {
                Mail::to($user->email)->send(new AdminWelcomeMail($user, $request->password));
                $emailSent = true;
                $emailMessage = 'Welcome email sent successfully';
            } catch (\Exception $e) {
                $emailSent = false;
                $emailMessage = 'User created but email failed to send: ' . $e->getMessage();
                Log::error('Admin welcome email failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin user created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at
                ],
                'email_sent' => $emailSent,
                'email_message' => $emailMessage
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create the specific admin requested
     */
    public function createSpecificAdmin()
    {
        try {
            // Check if user already exists
            $existingUser = User::where('email', 'fahmad_iqbal@hotmail.com')->first();
            if ($existingUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'User with this email already exists'
                ], 409);
            }

            // Create the specific admin user
            $user = User::create([
                'name' => 'Fahmad Iqbal',
                'email' => 'fahmad_iqbal@hotmail.com',
                'password' => Hash::make('123456'),
            ]);

            // Assign admin role if roles system is configured
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('admin');
                } catch (\Exception $e) {
                    // Role system might not be fully configured, continue anyway
                    Log::warning('Could not assign admin role: ' . $e->getMessage());
                }
            }

            // Send welcome email with credentials
            try {
                Mail::to($user->email)->send(new AdminWelcomeMail($user, '123456'));
                $emailSent = true;
                $emailMessage = 'Welcome email sent successfully to ' . $user->email;
            } catch (\Exception $e) {
                $emailSent = false;
                $emailMessage = 'User created but email failed to send: ' . $e->getMessage();
                Log::error('Admin welcome email failed: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin user "Fahmad Iqbal" created successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at
                ],
                'credentials' => [
                    'email' => 'fahmad_iqbal@hotmail.com',
                    'password' => '123456'
                ],
                'email_sent' => $emailSent,
                'email_message' => $emailMessage
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create admin user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * List all admin users
     */
    public function listAdmins()
    {
        try {
            $admins = User::all();
            
            return response()->json([
                'success' => true,
                'admins' => $admins->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admin users: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test email configuration
     */
    public function testEmail()
    {
        try {
            // Create a test user object (not saved to database)
            $testUser = new User([
                'name' => 'Test User',
                'email' => 'test@example.com'
            ]);

            // Try to send test email
            Mail::to('fahmad_iqbal@hotmail.com')->send(new AdminWelcomeMail($testUser, 'test123'));

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
