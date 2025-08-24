<?php
// Dashboard Test Script
echo "=== HEALTHCARE PLATFORM DASHBOARD TEST ===\n\n";

$baseUrl = 'http://127.0.0.1:8000';
$dashboardRoutes = [
    'Main Dashboard' => '/dashboard',
    'Owner Dashboard' => '/dashboard/owner',
    'Admin Dashboard' => '/admin-dashboard-direct',
    'Doctor Dashboard' => '/doctor-dashboard-direct', 
    'Lab Tech Dashboard' => '/lab-tech-dashboard',
    'Radiologist Dashboard' => '/radiologist-dashboard-direct',
    'Pharmacist Dashboard' => '/pharmacist-dashboard',
    'Doctor Enhanced' => '/doctor-enhanced-dashboard'
];

function testRoute($name, $route, $baseUrl) {
    $url = $baseUrl . $route;
    echo "Testing: $name\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "❌ CURL Error: $error\n";
        return false;
    }
    
    switch ($httpCode) {
        case 200:
            echo "✅ SUCCESS (200 OK)\n";
            return true;
        case 302:
        case 301:
            echo "🔄 REDIRECT ($httpCode) - Likely requires login\n";
            return true;
        case 404:
            echo "❌ NOT FOUND (404)\n";
            return false;
        case 500:
            echo "🔥 SERVER ERROR (500)\n";
            return false;
        default:
            echo "⚠️  HTTP $httpCode\n";
            return false;
    }
}

echo "Testing dashboard routes...\n\n";

$results = [];
foreach ($dashboardRoutes as $name => $route) {
    $results[$name] = testRoute($name, $route, $baseUrl);
    echo "\n";
}

echo "=== SUMMARY ===\n";
$working = 0;
$total = count($results);

foreach ($results as $name => $status) {
    $icon = $status ? "✅" : "❌";
    echo "$icon $name\n";
    if ($status) $working++;
}

echo "\nWorking: $working/$total dashboards\n";

// Test view files exist
echo "\n=== VIEW FILES CHECK ===\n";
$viewPath = __DIR__ . '/resources/views/';
$requiredViews = [
    'owner-dashboard.blade.php',
    'admin-dashboard.blade.php', 
    'doctor-dashboard.blade.php',
    'lab-tech-dashboard.blade.php',
    'radiologist-dashboard.blade.php',
    'pharmacist-dashboard.blade.php'
];

foreach ($requiredViews as $view) {
    $filePath = $viewPath . $view;
    $exists = file_exists($filePath);
    $icon = $exists ? "✅" : "❌";
    echo "$icon $view\n";
}

echo "\n=== TEST COMPLETE ===\n";
?>
