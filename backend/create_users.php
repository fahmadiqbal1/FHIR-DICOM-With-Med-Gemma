<?php
// Create original users script

$users = [
    ['name' => 'Admin User', 'email' => 'admin@medgemma.com', 'password' => 'admin123', 'role' => 'admin'],
    ['name' => 'Doctor 1', 'email' => 'doctor1@medgemma.com', 'password' => 'doctor123', 'role' => 'doctor'], 
    ['name' => 'Doctor 2', 'email' => 'doctor2@medgemma.com', 'password' => 'doctor123', 'role' => 'doctor'],
    ['name' => 'Lab Tech', 'email' => 'labtech@medgemma.com', 'password' => 'labtech123', 'role' => 'lab_tech'],
    ['name' => 'Radiologist', 'email' => 'radiologist@medgemma.com', 'password' => 'radiologist123', 'role' => 'radiologist'],
    ['name' => 'Pharmacist', 'email' => 'pharmacist@medgemma.com', 'password' => 'pharmacist123', 'role' => 'pharmacist']
];

foreach ($users as $userData) {
    $user = App\Models\User::updateOrCreate(
        ['email' => $userData['email']], 
        [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'role' => $userData['role'],
            'email_verified_at' => now()
        ]
    );
    echo "✅ Created: {$userData['email']} / {$userData['password']} (Role: {$userData['role']})" . PHP_EOL;
}

echo "🎉 All your original users have been created!" . PHP_EOL;
echo "🔗 Login at: http://127.0.0.1:8000/login" . PHP_EOL;
