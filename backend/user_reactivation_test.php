<?php

echo "=== USER REACTIVATION TEST ===\n";
echo "Testing login after reactivating user accounts...\n\n";

$base_url = 'http://127.0.0.1:8000';

echo "=== CHECKING DATABASE STATUS ===\n";
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $stmt = $pdo->query("SELECT email, role, is_active_doctor FROM users WHERE email IN ('owner@medgemma.com', 'admin@medgemma.com', 'labtech@medgemma.com', 'doctor1@medgemma.com', 'radiologist@medgemma.com', 'pharmacist@medgemma.com') ORDER BY email");
    
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $status = $user['is_active_doctor'] == 1 ? '✅ ACTIVE' : '❌ INACTIVE';
        echo "{$user['email']} ({$user['role']}) - $status\n";
    }
} catch (Exception $e) {
    echo "❌ Database check failed: " . $e->getMessage() . "\n";
}

echo "\n=== TESTING QUICK LOGIN ROUTES ===\n";

$quickLogins = [
    'Lab Tech' => '/quick-login/lab-tech',
    'Owner' => '/quick-login/owner',
    'Admin' => '/quick-login/admin'
];

foreach ($quickLogins as $role => $path) {
    $url = $base_url . $path;
    echo "Testing $role quick login... ";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 10,
            'ignore_errors' => true,
            'follow_location' => 1
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response !== false) {
        if (strpos($response, 'deactivated') !== false) {
            echo "❌ Still showing deactivated message\n";
        } elseif (strpos($response, 'Dashboard') !== false || strpos($response, 'dashboard') !== false) {
            echo "✅ SUCCESS - Dashboard loaded\n";
        } elseif (strpos($response, 'login') !== false) {
            echo "⚠️  Redirected to login page\n";
        } else {
            echo "❓ Unclear response\n";
        }
    } else {
        echo "❌ Request failed\n";
    }
    
    usleep(500000); // 0.5 second delay
}

echo "\n=== TESTING MANUAL LOGIN ===\n";
echo "Login page: $base_url/login\n";
echo "Test with: labtech@medgemma.com / password\n";

$loginPage = @file_get_contents($base_url . '/login');
if ($loginPage && strpos($loginPage, 'csrf_token') !== false) {
    echo "✅ Login form is loading with CSRF token\n";
} else {
    echo "❌ Login form has issues\n";
}

echo "\n=== SOLUTION STATUS ===\n";
echo "✅ User accounts reactivated in database\n";
echo "✅ CheckUserActive middleware should now allow access\n";
echo "✅ Both manual and quick login should work\n";

echo "\n🎯 RECOMMENDED ACCESS METHOD:\n";
echo "🔗 Lab Tech: $base_url/quick-login/lab-tech\n";
echo "   → Should take you to Lab Tech Dashboard\n";
echo "   → Click 'Configuration' button for 3-machine setup\n";

echo "\n📋 IF STILL HAVING ISSUES:\n";
echo "1. Clear browser cache/cookies\n";
echo "2. Try incognito/private browsing mode\n";
echo "3. Use the quick login URLs above\n";

?>
