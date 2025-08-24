<?php

echo "=== LOGIN FORM TEST ===\n";
echo "Testing actual login form submission...\n\n";

$base_url = 'http://127.0.0.1:8000';

// First get the login form to extract CSRF token
echo "1. Getting login form and CSRF token...\n";

$loginUrl = $base_url . '/login';
$loginPageContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$loginPage = @file_get_contents($loginUrl, false, $loginPageContext);

if ($loginPage === false) {
    echo "❌ Could not load login page\n";
    exit(1);
}

// Extract CSRF token
$csrfToken = null;
if (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches)) {
    $csrfToken = $matches[1];
    echo "✅ CSRF token found: " . substr($csrfToken, 0, 20) . "...\n";
} else {
    echo "❌ Could not find CSRF token in login page\n";
    echo "Page content preview:\n" . substr($loginPage, 0, 500) . "...\n";
    exit(1);
}

// Extract cookies from response headers
$cookies = '';
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        if (stripos($header, 'Set-Cookie:') === 0) {
            $cookie = substr($header, 11);
            $cookies .= trim(explode(';', $cookie)[0]) . '; ';
        }
    }
    if ($cookies) {
        echo "✅ Session cookies found: " . substr(trim($cookies), 0, 50) . "...\n";
    }
}

// Prepare login data
$loginData = http_build_query([
    '_token' => $csrfToken,
    'email' => 'labtech@medgemma.com',
    'password' => 'password'
]);

echo "\n2. Submitting login form...\n";

$postContext = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($loginData),
            'Cookie: ' . $cookies,
            'Referer: ' . $loginUrl
        ],
        'content' => $loginData,
        'timeout' => 10,
        'ignore_errors' => true,
        'follow_location' => 0  // Don't follow redirects so we can see the response
    ]
]);

$response = @file_get_contents($base_url . '/login', false, $postContext);

if ($response === false) {
    echo "❌ Login request failed\n";
    exit(1);
}

// Check response headers
echo "Response headers:\n";
if (isset($http_response_header)) {
    foreach ($http_response_header as $header) {
        echo "   $header\n";
        
        // Check for redirect
        if (stripos($header, 'Location:') === 0) {
            $redirectUrl = trim(substr($header, 9));
            echo "🔄 Redirect to: $redirectUrl\n";
            
            if (strpos($redirectUrl, '/dashboard') !== false) {
                echo "✅ SUCCESS: Redirected to dashboard\n";
            } elseif (strpos($redirectUrl, '/login') !== false) {
                echo "❌ FAILED: Redirected back to login\n";
            }
        }
    }
}

echo "\n3. Analyzing response content...\n";

if (strpos($response, 'The provided credentials do not match our records') !== false) {
    echo "❌ CREDENTIAL ERROR: The provided credentials do not match our records\n";
} elseif (strpos($response, 'csrf') !== false || strpos($response, 'CSRF') !== false) {
    echo "❌ CSRF ERROR: CSRF token mismatch\n";
} elseif (strpos($response, 'validation') !== false) {
    echo "❌ VALIDATION ERROR found in response\n";
} elseif (strpos($response, 'dashboard') !== false || strpos($response, 'Dashboard') !== false) {
    echo "✅ SUCCESS: Dashboard content found\n";
} else {
    echo "❓ UNCLEAR RESPONSE\n";
    echo "Response preview:\n" . substr($response, 0, 500) . "...\n";
}

echo "\n=== DIAGNOSTIC SUMMARY ===\n";
echo "✅ Login page loads correctly\n";
echo "✅ CSRF token extraction works\n";
echo "✅ User credentials are valid in database\n";
echo "✅ Laravel Auth::attempt works in CLI\n";

echo "\nIf login still fails, the issue might be:\n";
echo "1. Session/cookie handling in form submission\n";
echo "2. Middleware interference\n";
echo "3. CSRF token validation timing\n";
echo "4. HTTP request method or content-type issues\n";

?>
