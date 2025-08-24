<?php

echo "=== DEBUGGING QUICK LOGIN CONTROLLER ===\n";

// Manually test the QuickLoginController logic
require_once 'vendor/autoload.php';

// Bootstrap Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "✅ Laravel bootstrapped\n";

// Find the lab tech user
$labTech = \App\Models\User::where('email', 'labtech@medgemma.com')->first();

if ($labTech) {
    echo "✅ Lab tech user found:\n";
    echo "   ID: {$labTech->id}\n";
    echo "   Email: {$labTech->email}\n"; 
    echo "   Role: {$labTech->role}\n";
    echo "   Active: " . ($labTech->is_active_doctor ? 'Yes' : 'No') . "\n";
    
    // Try to log in
    \Illuminate\Support\Facades\Auth::login($labTech);
    echo "✅ Auth::login() called\n";
    
    // Check if user is authenticated
    if (\Illuminate\Support\Facades\Auth::check()) {
        $authUser = \Illuminate\Support\Facades\Auth::user();
        echo "✅ User is authenticated as: {$authUser->email}\n";
        
        // Check what the redirect should be
        echo "🔍 Testing redirect route...\n";
        
        try {
            $redirectUrl = route('lab-tech.dashboard');
            echo "   Route 'lab-tech.dashboard' resolves to: $redirectUrl\n";
        } catch (Exception $e) {
            echo "   ❌ Route 'lab-tech.dashboard' error: " . $e->getMessage() . "\n";
        }
        
    } else {
        echo "❌ User authentication failed after Auth::login()\n";
    }
    
} else {
    echo "❌ Lab tech user not found\n";
}

echo "\n=== ROUTE TESTING ===\n";

// Check if routes exist
try {
    $routes = app('router')->getRoutes();
    $labTechRoutes = [];
    
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'lab-tech') !== false) {
            $labTechRoutes[] = $route->uri() . ' -> ' . $route->getName();
        }
        if (strpos($route->uri(), 'quick-login') !== false) {
            echo "Quick login route: " . $route->uri() . " -> " . json_encode($route->middleware()) . "\n";
        }
    }
    
    echo "\nLab tech related routes:\n";
    foreach ($labTechRoutes as $route) {
        echo "   $route\n";
    }
    
} catch (Exception $e) {
    echo "❌ Route testing error: " . $e->getMessage() . "\n";
}

echo "\n=== MIDDLEWARE DEBUGGING ===\n";
echo "Checking what middleware is applied to quick login routes...\n";

try {
    $route = app('router')->getRoutes()->getByName(null);
    $quickLoginRoute = null;
    
    foreach (app('router')->getRoutes() as $route) {
        if ($route->uri() === 'quick-login/lab-tech') {
            $quickLoginRoute = $route;
            break;
        }
    }
    
    if ($quickLoginRoute) {
        echo "Quick login lab-tech route middleware: " . json_encode($quickLoginRoute->middleware()) . "\n";
    } else {
        echo "❌ Could not find quick-login/lab-tech route\n";
    }
    
} catch (Exception $e) {
    echo "❌ Middleware debugging error: " . $e->getMessage() . "\n";
}

?>
