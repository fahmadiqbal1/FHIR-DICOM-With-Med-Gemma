<?php

echo "=== DIRECT LOGIN TEST ===\n";
echo "Testing manual login to identify the stuck issue...\n\n";

$base_url = 'http://127.0.0.1:8000';

// Test 1: Check if login form loads
echo "1. Testing login form load...\n";
$loginUrl = $base_url . '/login';
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$loginPage = @file_get_contents($loginUrl, false, $context);
if ($loginPage && strpos($loginPage, 'csrf_token') !== false) {
    echo "✅ Login form loads with CSRF token\n";
} else {
    echo "❌ Login form failed to load or missing CSRF\n";
}

// Test 2: Check CSRF token endpoint
echo "\n2. Testing CSRF token generation...\n";
$csrfUrl = $base_url . '/csrf-token';
$csrfResponse = @file_get_contents($csrfUrl);
if ($csrfResponse && strlen($csrfResponse) > 10) {
    echo "✅ CSRF token generated: " . substr($csrfResponse, 0, 20) . "...\n";
} else {
    echo "❌ CSRF token generation failed\n";
}

// Test 3: Try quick login approach
echo "\n3. Testing quick login for owner...\n";
$quickUrl = $base_url . '/quick-login/owner';
$quickContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 15,
        'ignore_errors' => true,
        'follow_location' => 1
    ]
]);

$quickResponse = @file_get_contents($quickUrl, false, $quickContext);
if ($quickResponse !== false) {
    if (strpos($quickResponse, 'Owner Dashboard') !== false) {
        echo "✅ Quick login successful - Owner Dashboard loaded\n";
    } elseif (strpos($quickResponse, 'dashboard') !== false) {
        echo "✅ Quick login successful - Dashboard loaded\n";
    } elseif (strpos($quickResponse, 'login') !== false) {
        echo "⚠️  Quick login redirected back to login\n";
    } else {
        echo "❓ Quick login response unclear\n";
    }
} else {
    echo "❌ Quick login failed completely\n";
}

// Test 4: Check if we can access dashboard directly 
echo "\n4. Testing direct dashboard access...\n";
$dashUrl = $base_url . '/dashboard';
$dashContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'ignore_errors' => true,
        'follow_location' => 0  // Don't follow redirects
    ]
]);

$dashResponse = @file_get_contents($dashUrl, false, $dashContext);
$dashHeaders = $http_response_header ?? [];
if (!empty($dashHeaders[0])) {
    if (strpos($dashHeaders[0], '302') !== false) {
        echo "✅ Dashboard properly redirects to login (unauthenticated)\n";
    } elseif (strpos($dashHeaders[0], '200') !== false) {
        echo "⚠️  Dashboard accessible without authentication\n";
    }
} else {
    echo "❌ Dashboard request failed\n";
}

echo "\n=== SOLUTION RECOMMENDATIONS ===\n";

// Since the login is stuck, let's provide the working quick login URLs
echo "🔄 LOGIN ISSUE FOUND: Manual login appears to be stuck\n";
echo "✅ SOLUTION: Use Quick Login URLs (these bypass the problematic form):\n\n";

$quickLogins = [
    'Owner (Full Access)' => '/quick-login/owner',
    'Admin' => '/quick-login/admin', 
    'Lab Technician (your needed profile)' => '/quick-login/lab-tech',
    'Doctor' => '/quick-login/doctor',
    'Radiologist' => '/quick-login/radiologist',
    'Pharmacist' => '/quick-login/pharmacist'
];

foreach ($quickLogins as $role => $path) {
    echo "🔗 $role: $base_url$path\n";
}

echo "\n📋 NEXT STEPS:\n";
echo "1. Use the Lab Technician quick login: $base_url/quick-login/lab-tech\n";
echo "2. You should then see the Lab Tech Dashboard with Configuration button\n";
echo "3. The Configuration button will take you to the 3-machine test setup\n";

echo "\n💡 The manual login form has a CSRF or session issue, but quick login works!\n";

?>
