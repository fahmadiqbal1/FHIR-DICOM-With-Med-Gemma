<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateTestUsers extends Command
{
    protected $signature = 'create:test-users';
    protected $description = 'Create test users for all roles with standard passwords';

    public function handle()
    {
        $this->info('Creating test users for all roles...');

        $testUsers = [
            // Admin Users
            ['name' => 'Admin User', 'email' => 'admin@medgemma.com', 'password' => 'admin123', 'role' => 'Admin'],
            ['name' => 'System Administrator', 'email' => 'administrator@medgemma.com', 'password' => 'admin123', 'role' => 'Admin'],
            
            // Doctors
            ['name' => 'Dr. Sarah Johnson', 'email' => 'doctor1@medgemma.com', 'password' => 'doctor123', 'role' => 'Doctor'],
            ['name' => 'Dr. Michael Chen', 'email' => 'doctor2@medgemma.com', 'password' => 'doctor123', 'role' => 'Doctor'],
            ['name' => 'Dr. Emily Rodriguez', 'email' => 'doctor@medgemma.com', 'password' => 'doctor123', 'role' => 'Doctor'],
            
            // Radiologists
            ['name' => 'Dr. James Wilson', 'email' => 'radiologist@medgemma.com', 'password' => 'radio123', 'role' => 'Radiologist'],
            ['name' => 'Dr. Lisa Parker', 'email' => 'radiologist2@medgemma.com', 'password' => 'radio123', 'role' => 'Radiologist'],
            
            // Nurses
            ['name' => 'Nurse Jennifer Smith', 'email' => 'nurse@medgemma.com', 'password' => 'nurse123', 'role' => 'Nurse'],
            ['name' => 'Nurse Robert Brown', 'email' => 'nurse2@medgemma.com', 'password' => 'nurse123', 'role' => 'Nurse'],
            
            // Lab Technicians
            ['name' => 'Lab Tech Maria Garcia', 'email' => 'labtech@medgemma.com', 'password' => 'lab123', 'role' => 'Lab Technician'],
            ['name' => 'Lab Tech David Lee', 'email' => 'labtech2@medgemma.com', 'password' => 'lab123', 'role' => 'Lab Technician'],
            
            // Pharmacists
            ['name' => 'Pharmacist Anna Davis', 'email' => 'pharmacist@medgemma.com', 'password' => 'pharma123', 'role' => 'Pharmacist'],
            ['name' => 'Pharmacist John Martinez', 'email' => 'pharmacist2@medgemma.com', 'password' => 'pharma123', 'role' => 'Pharmacist'],
            
            // Pathologists
            ['name' => 'Dr. Patricia Taylor', 'email' => 'pathologist@medgemma.com', 'password' => 'patho123', 'role' => 'Pathologist'],
            ['name' => 'Dr. Mark Anderson', 'email' => 'pathologist2@medgemma.com', 'password' => 'patho123', 'role' => 'Pathologist'],
            
            // Receptionists
            ['name' => 'Receptionist Susan White', 'email' => 'receptionist@medgemma.com', 'password' => 'reception123', 'role' => 'Receptionist'],
            ['name' => 'Receptionist Carlos Hernandez', 'email' => 'receptionist2@medgemma.com', 'password' => 'reception123', 'role' => 'Receptionist'],
            
            // Patients
            ['name' => 'Patient John Doe', 'email' => 'patient@medgemma.com', 'password' => 'patient123', 'role' => 'Patient'],
            ['name' => 'Patient Jane Smith', 'email' => 'patient2@medgemma.com', 'password' => 'patient123', 'role' => 'Patient'],
        ];

        foreach ($testUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role if it exists
            $role = Role::where('name', $userData['role'])->first();
            if ($role) {
                if (!$user->hasRole($userData['role'])) {
                    $user->assignRole($userData['role']);
                }
                $this->info("✅ {$userData['role']}: {$user->email} / {$userData['password']}");
            } else {
                $this->warn("⚠️  Role '{$userData['role']}' not found for {$user->email}");
            }
        }

        $this->info("\n🎉 Test users created successfully!");
        $this->info("🔗 Quick login page: http://localhost:8000/quick-login");
        $this->info("🔗 Manual login page: http://localhost:8000/login");
    }
}
