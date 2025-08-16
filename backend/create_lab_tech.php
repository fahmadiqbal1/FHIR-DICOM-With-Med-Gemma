<?php

use App\Models\User;

$labTech = User::firstOrCreate(
    ['email' => 'labtech@medgemma.com'],
    [
        'name' => 'Lab Technician Demo',
        'password' => bcrypt('labtech123'),
        'email_verified_at' => now()
    ]
);

echo 'Lab Tech user: ' . $labTech->email . ' (ID: ' . $labTech->id . ')' . PHP_EOL;
