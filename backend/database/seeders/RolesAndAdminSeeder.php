<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password')]
        );

        // If Spatie package is not available or tables are not present, skip role/permission seeding gracefully.
        if (!class_exists('Spatie\Permission\Models\Role') || 
            !class_exists('Spatie\Permission\Models\Permission') ||
            !Schema::hasTable('roles') || 
            !Schema::hasTable('permissions') || 
            !Schema::hasTable('model_has_roles')) {
            return; // Admin user created without roles - package not fully installed
        }

        // Use the classes dynamically to avoid compile-time errors
        $roleClass = 'Spatie\Permission\Models\Role';
        $permissionClass = 'Spatie\Permission\Models\Permission';

        $roles = [
            'Admin',
            'Doctor',
            'Radiologist',
            'Pharmacist',
            'Lab Technician',
            'Pathologist',
            'Nurse',
            'Receptionist',
            'Patient',
        ];

        $permissions = [
            'view dashboard',
            'manage patients',
            'manage imaging',
            'manage prescriptions',
            'manage inventory',
            'manage lab orders',
            'manage appointments',
            'manage clinical notes',
        ];

        foreach ($permissions as $perm) {
            $permissionClass::findOrCreate($perm);
        }

        foreach ($roles as $r) {
            $roleClass::findOrCreate($r);
        }

        // Assign broad permissions to Admin
        $adminRole = $roleClass::findByName('Admin');
        $adminRole->givePermissionTo($permissionClass::all());

        // Ensure the admin has Admin role
        $admin->assignRole('Admin');

        // Basic mappings for other roles
        $roleClass::findByName('Doctor')->givePermissionTo([
            'view dashboard','manage patients','manage imaging','manage prescriptions','manage lab orders','manage appointments','manage clinical notes'
        ]);
        $roleClass::findByName('Radiologist')->givePermissionTo(['view dashboard','manage imaging']);
        $roleClass::findByName('Pharmacist')->givePermissionTo(['view dashboard','manage prescriptions','manage inventory']);
        $roleClass::findByName('Lab Technician')->givePermissionTo(['view dashboard','manage lab orders']);
        $roleClass::findByName('Pathologist')->givePermissionTo(['view dashboard','manage lab orders']);
        $roleClass::findByName('Nurse')->givePermissionTo(['view dashboard','manage patients','manage appointments']);
        $roleClass::findByName('Receptionist')->givePermissionTo(['view dashboard','manage appointments']);
        // Patient gets minimal dashboard
        $roleClass::findByName('Patient')->givePermissionTo(['view dashboard']);
    }
}
