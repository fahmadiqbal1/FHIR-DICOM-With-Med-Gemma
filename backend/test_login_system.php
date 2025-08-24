<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

echo "=== Login System Test ===\n";

// Test 1: Check all users
echo "\n1. Checking all users:\n";
$users = User::all();
foreach ($users as $user) {
    echo "- {$user->name} ({$user->email}) - Role: {$user->role} - Can Login: " . 
         (Hash::check('password', $user->password) ? 'YES' : 'NO') . "\n";
}

// Test 2: Test owner login specifically
echo "\n2. Testing owner login:\n";
$owner = User::where('email', 'owner@medgemma.com')->first();
if ($owner) {
    echo "- Owner found: {$owner->name}\n";
    echo "- Role: {$owner->role}\n";
    echo "- Password check: " . (Hash::check('password', $owner->password) ? 'PASS' : 'FAIL') . "\n";
    echo "- Has permissions: " . ($owner->hasRole('owner') ? 'YES' : 'NO') . "\n";
} else {
    echo "- Owner not found!\n";
}

// Test 3: Test other profiles
echo "\n3. Testing other profiles:\n";
$profiles = ['lab-tech', 'doctor', 'radiologist', 'pharmacist'];
foreach ($profiles as $role) {
    $user = User::where('role', $role)->first();
    if ($user) {
        echo "- {$role}: {$user->email} - Password: " . 
             (Hash::check('password', $user->password) ? 'PASS' : 'FAIL') . "\n";
    } else {
        echo "- {$role}: NOT FOUND\n";
    }
}

// Test 4: Check dashboard route access
echo "\n4. Dashboard route configuration:\n";
$routes = collect(\Illuminate\Support\Facades\Route::getRoutes())->filter(function($route) {
    return str_contains($route->uri(), 'dashboard');
})->map(function($route) {
    return $route->uri() . ' -> ' . $route->getName();
});

foreach ($routes as $route) {
    echo "- {$route}\n";
}

echo "\n=== Test Complete ===\n";
