<?php

echo "=== QUICK LOGIN DEBUGGING ===\n";
echo "Testing the QuickLoginController directly...\n\n";

// Test database connection and user lookup
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    echo "✅ Database connected\n";
    
    // Test finding the lab tech user
    $stmt = $pdo->prepare("SELECT id, email, role, is_active_doctor FROM users WHERE email = ?");
    $stmt->execute(['labtech@medgemma.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✅ Lab tech user found:\n";
        echo "   ID: {$user['id']}\n";
        echo "   Email: {$user['email']}\n";
        echo "   Role: {$user['role']}\n";
        echo "   Active: " . ($user['is_active_doctor'] ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ Lab tech user not found\n";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== TESTING LARAVEL USER MODEL ===\n";

// Test if we can use Laravel to find the user
try {
    require_once 'vendor/autoload.php';
    
    // Bootstrap Laravel app
    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    $user = \App\Models\User::where('email', 'labtech@medgemma.com')->first();
    
    if ($user) {
        echo "✅ Laravel User model found lab tech:\n";
        echo "   ID: {$user->id}\n";
        echo "   Email: {$user->email}\n";
        echo "   Role: {$user->role}\n";
        echo "   Active: " . ($user->is_active_doctor ? 'Yes' : 'No') . "\n";
        echo "   Name: {$user->name}\n";
    } else {
        echo "❌ Laravel User model could not find lab tech\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel User model error: " . $e->getMessage() . "\n";
}

echo "\n=== TESTING AUTH ATTEMPT ===\n";

try {
    $credentials = ['email' => 'labtech@medgemma.com', 'password' => 'password'];
    
    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        echo "✅ Auth::attempt successful\n";
        $authUser = \Illuminate\Support\Facades\Auth::user();
        echo "   Authenticated as: {$authUser->email} (Role: {$authUser->role})\n";
        \Illuminate\Support\Facades\Auth::logout();
    } else {
        echo "❌ Auth::attempt failed\n";
        echo "   Checking password hash...\n";
        
        // Check if password is correct
        $user = \App\Models\User::where('email', 'labtech@medgemma.com')->first();
        if ($user && \Illuminate\Support\Facades\Hash::check('password', $user->password)) {
            echo "   ✅ Password hash is correct\n";
            echo "   ❓ Auth::attempt should work - checking other issues\n";
        } else {
            echo "   ❌ Password hash verification failed\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Auth attempt error: " . $e->getMessage() . "\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "If Laravel User model works but auth fails, the issue might be:\n";
echo "1. Session/cache issues - try clearing all caches\n";
echo "2. Middleware interference\n";  
echo "3. Authentication guard configuration\n";
echo "\nDirect solutions:\n";
echo "🔧 Try browser incognito mode\n";
echo "🔧 Clear all browser data for localhost\n";
echo "🔧 Restart Laravel server: php artisan serve\n";

?>
