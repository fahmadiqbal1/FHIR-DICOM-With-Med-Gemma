<?php

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use App\Models\User;

$app = require_once __DIR__.'/bootstrap/app.php';
$app->boot();

echo "Creating your original users...\n";

$users = [
    ['name' => 'Admin User', 'email' => 'admin@medgemma.com', 'password' => 'admin123', 'role' => 'admin'],
    ['name' => 'Doctor 1', 'email' => 'doctor1@medgemma.com', 'password' => 'doctor123', 'role' => 'doctor'],
    ['name' => 'Doctor 2', 'email' => 'doctor2@medgemma.com', 'password' => 'doctor123', 'role' => 'doctor'],
    ['name' => 'Lab Tech', 'email' => 'labtech@medgemma.com', 'password' => 'labtech123', 'role' => 'lab_tech'],
    ['name' => 'Radiologist', 'email' => 'radiologist@medgemma.com', 'password' => 'radiologist123', 'role' => 'radiologist'],
    ['name' => 'Pharmacist', 'email' => 'pharmacist@medgemma.com', 'password' => 'pharmacist123', 'role' => 'pharmacist'],
    ['name' => 'Owner', 'email' => 'owner@medgemma.com', 'password' => 'owner123', 'role' => 'owner']
];

foreach ($users as $userData) {
    $user = User::updateOrCreate(
        ['email' => $userData['email']], 
        [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'role' => $userData['role'],
            'email_verified_at' => now()
        ]
    );
    echo "✅ Created: {$userData['email']} / {$userData['password']} (Role: {$userData['role']})\n";
}

echo "🎉 All your original users have been created!\n";
echo "🔗 Login at: http://127.0.0.1:8000/login\n";
