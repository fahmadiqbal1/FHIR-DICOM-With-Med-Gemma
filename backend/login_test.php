<?php
// Login Flow Test Script
echo "=== LOGIN FLOW TEST ===\n\n";

require_once __DIR__ . '/vendor/autoload.php';

// Test users
$testUsers = [
    ['name' => 'Owner', 'email' => 'owner@medgemma.com', 'password' => 'password'],
    ['name' => 'Admin', 'email' => 'admin@medgemma.com', 'password' => 'password'],
    ['name' => 'Doctor', 'email' => 'doctor1@medgemma.com', 'password' => 'password'],
    ['name' => 'Lab Tech', 'email' => 'labtech@medgemma.com', 'password' => 'password'],
    ['name' => 'Radiologist', 'email' => 'radiologist@medgemma.com', 'password' => 'password'],
    ['name' => 'Pharmacist', 'email' => 'pharmacist@medgemma.com', 'password' => 'password']
];

$baseUrl = 'http://127.0.0.1:8000';

function testLogin($user, $baseUrl) {
    echo "Testing login for: {$user['name']} ({$user['email']})\n";
    
    // First get CSRF token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
    $loginPage = curl_exec($ch);
    curl_close($ch);
    
    // Extract CSRF token
    if (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches)) {
        $csrfToken = $matches[1];
        echo "✓ Got CSRF token\n";
    } else {
        echo "❌ Could not get CSRF token\n";
        return false;
    }
    
    // Attempt login
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'email' => $user['email'],
        'password' => $user['password'],
        '_token' => $csrfToken
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
    curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded',
        'X-CSRF-TOKEN: ' . $csrfToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    curl_close($ch);
    
    switch ($httpCode) {
        case 302:
            echo "✅ Login successful - Redirect to: $redirectUrl\n";
            
            // Check if redirected to dashboard
            if (strpos($redirectUrl, '/dashboard') !== false) {
                echo "✅ Correctly redirected to dashboard\n";
                return true;
            } else {
                echo "⚠️  Redirected to: $redirectUrl (not dashboard)\n";
                return false;
            }
            break;
        case 422:
            echo "❌ Validation errors (422)\n";
            return false;
        case 401:
            echo "❌ Authentication failed (401)\n";
            return false;
        default:
            echo "❌ Unexpected response: $httpCode\n";
            return false;
    }
}

// Test each user
$results = [];
foreach ($testUsers as $user) {
    $results[$user['name']] = testLogin($user, $baseUrl);
    echo "\n";
}

echo "=== LOGIN TEST SUMMARY ===\n";
$successful = 0;
foreach ($results as $name => $success) {
    $icon = $success ? "✅" : "❌";
    echo "$icon $name login\n";
    if ($success) $successful++;
}

echo "\nSuccessful logins: $successful/" . count($results) . "\n";

// Clean up
@unlink('/tmp/cookies.txt');

echo "\n=== TEST COMPLETE ===\n";
?>
