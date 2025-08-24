<?php

echo "=== AUTHENTICATION DEBUGGING TEST ===\n";
echo "Testing login credentials and database state...\n\n";

// Test database connection
try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    echo "✅ Database connection: OK\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Test if users exist and what their data looks like
echo "\n=== CHECKING USER DATA ===\n";

$users = $pdo->query("SELECT id, name, email, role, password, created_at FROM users ORDER BY id LIMIT 5");
$userList = [];

while ($user = $users->fetch(PDO::FETCH_ASSOC)) {
    // Try to decode the name if it's encrypted
    $name = $user['name'];
    $decodedName = null;
    
    if (strlen($name) > 50 && strpos($name, 'eyJ') === 0) {
        // This looks like base64 encrypted data
        $decodedName = "ENCRYPTED";
    }
    
    echo "ID: {$user['id']}\n";
    echo "Name: " . ($decodedName ?? $name) . "\n";
    echo "Email: {$user['email']}\n";  
    echo "Role: {$user['role']}\n";
    echo "Password Length: " . strlen($user['password']) . " chars\n";
    echo "---\n";
    
    $userList[] = [
        'id' => $user['id'],
        'email' => $user['email'], 
        'role' => $user['role'],
        'name' => $name
    ];
}

echo "\n=== TESTING WORKING CREDENTIALS ===\n";

$testCredentials = [
    'owner@medgemma.com' => 'owner',
    'admin@medgemma.com' => 'admin',
    'labtech@medgemma.com' => 'lab_tech',
    'doctor1@medgemma.com' => 'doctor',
    'radiologist@medgemma.com' => 'radiologist'
];

foreach ($testCredentials as $email => $expectedRole) {
    $user = $pdo->prepare("SELECT id, email, role, password FROM users WHERE email = ?");
    $user->execute([$email]);
    $userData = $user->fetch(PDO::FETCH_ASSOC);
    
    if ($userData) {
        echo "✅ Found: $email (Role: {$userData['role']})\n";
        
        // Test if password 'password' hashes correctly
        $testHash = password_verify('password', $userData['password']);
        if ($testHash) {
            echo "  ✅ Password 'password' verified\n";
        } else {
            echo "  ❌ Password 'password' failed verification\n";
        }
    } else {
        echo "❌ Missing: $email\n";
    }
}

echo "\n=== QUICK LOGIN TEST ===\n";

// Test the quick login route
$base_url = 'http://127.0.0.1:8000';
$quickLoginUrl = $base_url . '/quick-login/owner';

echo "Testing quick login for owner...\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'ignore_errors' => true,
        'follow_location' => 1
    ]
]);

$response = @file_get_contents($quickLoginUrl, false, $context);

if ($response !== false) {
    if (strpos($response, 'owner-dashboard') !== false || strpos($response, 'Owner Dashboard') !== false) {
        echo "✅ Quick login working - reached owner dashboard\n";
    } else {
        echo "⚠️  Quick login redirected but not to dashboard\n";
    }
} else {
    echo "❌ Quick login failed\n";
}

echo "\n=== RECOMMENDATIONS ===\n";

if (count($userList) > 0) {
    $firstUser = $userList[0];
    if (strlen($firstUser['name']) > 50) {
        echo "🔍 Issue Found: User names appear to be encrypted in database\n";
        echo "🔧 This might indicate model encryption is enabled\n";
        echo "📝 Check if Laravel's model casts or encryption is interfering\n";
        echo "🔄 Try using quick-login routes instead of manual login\n";
    }
}

echo "\n=== QUICK LOGIN URLS ===\n";
echo "Owner: $base_url/quick-login/owner\n";
echo "Admin: $base_url/quick-login/admin\n"; 
echo "Lab Tech: $base_url/quick-login/lab-tech\n";
echo "Doctor: $base_url/quick-login/doctor\n";
echo "Radiologist: $base_url/quick-login/radiologist\n";

?>
