<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing login functionality...\n";

$testAccounts = [
    'owner@medgemma.com' => 'password',
    'doctor1@medgemma.com' => 'password',
    'labtech@medgemma.com' => 'password',
    'radiologist@medgemma.com' => 'password',
    'pharmacist@medgemma.com' => 'password'
];

foreach ($testAccounts as $email => $password) {
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "\n--- Testing $email ---\n";
        echo "User ID: {$user->id}\n";
        echo "Role column: " . ($user->role ?? 'null') . "\n";
        echo "Spatie roles: " . $user->roles->pluck('name')->join(', ') . "\n";
        
        // Test password
        if (Hash::check($password, $user->password)) {
            echo "✅ Password correct\n";
        } else {
            echo "❌ Password incorrect\n";
        }
        
        // Test Auth attempt
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            echo "✅ Auth::attempt successful\n";
            $authUser = Auth::user();
            echo "Authenticated as: {$authUser->email}\n";
            
            // Test role detection
            if ($authUser->role === 'owner' || $authUser->hasRole('owner') || $authUser->hasRole('Owner')) {
                echo "🏠 Would redirect to: owner-dashboard\n";
            } elseif ($authUser->role === 'admin' || $authUser->hasRole('admin') || $authUser->hasRole('Admin')) {
                echo "👨‍💼 Would redirect to: admin-dashboard\n";
            } elseif ($authUser->role === 'doctor' || $authUser->hasRole('doctor') || $authUser->hasRole('Doctor')) {
                echo "👨‍⚕️ Would redirect to: doctor-dashboard\n";
            } elseif ($authUser->role === 'radiologist' || $authUser->hasRole('radiologist') || $authUser->hasRole('Radiologist')) {
                echo "🔬 Would redirect to: radiologist-dashboard\n";
            } elseif ($authUser->role === 'lab_tech' || $authUser->hasRole('lab_tech') || $authUser->hasRole('Lab Technician')) {
                echo "🧪 Would redirect to: lab-tech-dashboard\n";
            } elseif ($authUser->role === 'pharmacist' || $authUser->hasRole('pharmacist') || $authUser->hasRole('Pharmacist')) {
                echo "💊 Would redirect to: pharmacist-dashboard\n";
            } else {
                echo "🏥 Would redirect to: admin-dashboard (default)\n";
            }
            
            Auth::logout();
        } else {
            echo "❌ Auth::attempt failed\n";
        }
    } else {
        echo "❌ User $email not found\n";
    }
}

echo "\nTest completed!\n";
