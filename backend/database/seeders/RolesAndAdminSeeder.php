<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void
    {
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
            Permission::findOrCreate($perm);
        }

        foreach ($roles as $r) {
            Role::findOrCreate($r);
        }

        // Assign broad permissions to Admin
        $adminRole = Role::findByName('Admin');
        $adminRole->givePermissionTo(Permission::all());

        // Basic mappings for other roles
        Role::findByName('Doctor')->givePermissionTo([
            'view dashboard','manage patients','manage imaging','manage prescriptions','manage lab orders','manage appointments','manage clinical notes'
        ]);
        Role::findByName('Radiologist')->givePermissionTo(['view dashboard','manage imaging']);
        Role::findByName('Pharmacist')->givePermissionTo(['view dashboard','manage prescriptions','manage inventory']);
        Role::findByName('Lab Technician')->givePermissionTo(['view dashboard','manage lab orders']);
        Role::findByName('Pathologist')->givePermissionTo(['view dashboard','manage lab orders']);
        Role::findByName('Nurse')->givePermissionTo(['view dashboard','manage patients','manage appointments']);
        Role::findByName('Receptionist')->givePermissionTo(['view dashboard','manage appointments']);
        // Patient gets minimal dashboard
        Role::findByName('Patient')->givePermissionTo(['view dashboard']);

        // Create an initial admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator', 'password' => Hash::make('password')]
        );
        $admin->assignRole('Admin');
    }
}
