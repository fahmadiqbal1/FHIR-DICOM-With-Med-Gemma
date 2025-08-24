<?php

echo "=== CREDENTIAL VERIFICATION TEST ===\n";
echo "Checking user credentials in database...\n\n";

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    
    // Get lab tech user details
    $stmt = $pdo->prepare("SELECT id, email, password, role, is_active_doctor FROM users WHERE email = ?");
    $stmt->execute(['labtech@medgemma.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "✅ Lab Tech User Found:\n";
        echo "   ID: {$user['id']}\n";
        echo "   Email: {$user['email']}\n";
        echo "   Role: {$user['role']}\n";
        echo "   Active: " . ($user['is_active_doctor'] ? 'Yes' : 'No') . "\n";
        echo "   Password Hash: " . substr($user['password'], 0, 60) . "...\n";
        
        // Test if password 'password' matches the hash
        $testPassword = 'password';
        if (password_verify($testPassword, $user['password'])) {
            echo "✅ Password 'password' MATCHES the stored hash\n";
        } else {
            echo "❌ Password 'password' does NOT match the stored hash\n";
            
            // Try common alternatives
            $testPasswords = ['123456', 'admin', 'labtech', 'test', 'medgemma'];
            echo "Testing alternative passwords...\n";
            foreach ($testPasswords as $testPass) {
                if (password_verify($testPass, $user['password'])) {
                    echo "✅ Password '$testPass' MATCHES!\n";
                    break;
                }
            }
        }
        
    } else {
        echo "❌ Lab Tech user not found\n";
    }
    
    echo "\n=== TESTING OTHER USERS ===\n";
    
    // Check other key users
    $emails = ['admin@medgemma.com', 'owner@medgemma.com', 'doctor1@medgemma.com'];
    
    foreach ($emails as $email) {
        $stmt = $pdo->prepare("SELECT email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "User: {$user['email']} ({$user['role']})\n";
            if (password_verify('password', $user['password'])) {
                echo "   ✅ Password: 'password'\n";
            } else {
                echo "   ❌ Password: NOT 'password'\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== LARAVEL AUTH TEST ===\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    // Test Laravel authentication
    $credentials = ['email' => 'labtech@medgemma.com', 'password' => 'password'];
    
    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        echo "✅ Laravel Auth::attempt() SUCCESSFUL\n";
        $authUser = \Illuminate\Support\Facades\Auth::user();
        echo "   Authenticated as: {$authUser->email}\n";
        \Illuminate\Support\Facades\Auth::logout();
    } else {
        echo "❌ Laravel Auth::attempt() FAILED\n";
        echo "   This suggests a Laravel-specific authentication issue\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel auth test error: " . $e->getMessage() . "\n";
}

echo "\n=== SOLUTION RECOMMENDATIONS ===\n";
echo "If password verification works but Laravel auth fails:\n";
echo "1. Check User model for any custom authentication logic\n";
echo "2. Verify Laravel's auth configuration\n";
echo "3. Check if there are any custom auth guards\n";
echo "4. Reset user password using Laravel's Hash facade\n";

?>
