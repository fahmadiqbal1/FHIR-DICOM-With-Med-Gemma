<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
        $radiologistRole = Role::firstOrCreate(['name' => 'radiologist']);
        $labTechRole = Role::firstOrCreate(['name' => 'lab-tech']);

        // Test Users for each role
        $testUsers = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => 'admin123',
                'role' => 'admin',
                'department' => 'Administration',
                'phone' => '+1-555-0101',
                'bio' => 'System Administrator with full access to all platform features.'
            ],
            [
                'name' => 'Dr. John Smith',
                'email' => 'doctor@test.com', 
                'password' => 'doctor123',
                'role' => 'doctor',
                'department' => 'Internal Medicine',
                'phone' => '+1-555-0102',
                'bio' => 'Board-certified internal medicine physician specializing in patient care and diagnostics.'
            ],
            [
                'name' => 'Dr. Sarah Wilson',
                'email' => 'radiologist@test.com',
                'password' => 'radio123',
                'role' => 'radiologist', 
                'department' => 'Radiology',
                'phone' => '+1-555-0103',
                'bio' => 'Diagnostic radiologist with expertise in medical imaging and AI-assisted analysis.'
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'labtech@test.com',
                'password' => 'lab123',
                'role' => 'lab-tech',
                'department' => 'Laboratory',
                'phone' => '+1-555-0104', 
                'bio' => 'Certified laboratory technician responsible for sample processing and quality control.'
            ],
            [
                'name' => 'Fahmad Iqbal',
                'email' => 'fahmad_iqbal@hotmail.com',
                'password' => 'password123',
                'role' => 'admin',
                'department' => 'System Development',
                'phone' => '+92-300-1234567',
                'bio' => 'Lead developer and system architect for the MedGemma Healthcare AI platform.'
            ]
        ];

        foreach ($testUsers as $userData) {
            // Check if user already exists
            $existingUser = User::where('email', $userData['email'])->first();
            
            if (!$existingUser) {
                $user = User::create([
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'department' => $userData['department'],
                    'phone' => $userData['phone'],
                    'bio' => $userData['bio'],
                    'email_notifications' => true,
                    'system_alerts' => true,
                    'audit_alerts' => $userData['role'] === 'admin' ? true : false,
                    'two_factor_enabled' => false,
                    'session_timeout' => 120,
                    'revenue_share' => $userData['role'] === 'doctor' ? 70.00 : null,
                ]);

                // Assign role
                $user->assignRole($userData['role']);
                
                echo "Created user: {$userData['name']} ({$userData['email']}) with role: {$userData['role']}\n";
            } else {
                echo "User already exists: {$userData['email']}\n";
            }
        }
    }
}
