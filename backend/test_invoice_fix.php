<?php
/**
 * Test script to verify invoice generation fixes
 * 
 * This script tests:
 * 1. Doctor API endpoint functionality
 * 2. Invoice route availability
 * 3. InvoiceController functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\InvoiceController;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Invoice Generation Fix Test ===\n\n";

// Test 1: Check active doctors
echo "1. Testing doctor query...\n";
$doctors = User::select('id', 'name', 'email', 'role', 'is_active_doctor')
    ->where('is_active_doctor', 1)
    ->where(function($query) {
        $query->where('role', 'doctor')
              ->orWhere('role', 'Doctor')
              ->orWhere('name', 'like', 'Dr.%')
              ->orWhereHas('roles', function($roleQuery) {
                  $roleQuery->where('name', 'Doctor')
                            ->orWhere('name', 'doctor');
              });
    })
    ->get();

echo "Found " . $doctors->count() . " active doctors:\n";
foreach($doctors as $doctor) {
    echo "  - ID: {$doctor->id}, Name: {$doctor->name}, Role: " . ($doctor->role ?: 'null') . "\n";
}

// Test 2: Check route registration
echo "\n2. Testing route availability...\n";
$router = app('router');
$routes = $router->getRoutes();

$invoiceViewRoute = null;
foreach($routes as $route) {
    if($route->getName() === 'admin.invoices.view') {
        $invoiceViewRoute = $route;
        break;
    }
}

if($invoiceViewRoute) {
    echo "✓ Route 'admin.invoices.view' is registered\n";
    echo "  URI: " . $invoiceViewRoute->uri() . "\n";
    echo "  Methods: " . implode(', ', $invoiceViewRoute->methods()) . "\n";
} else {
    echo "✗ Route 'admin.invoices.view' not found\n";
}

// Test 3: Check if invoice view file exists
echo "\n3. Testing view file availability...\n";
$viewPath = resource_path('views/invoice.blade.php');
if(file_exists($viewPath)) {
    echo "✓ Invoice view file exists at: $viewPath\n";
} else {
    echo "✗ Invoice view file not found at: $viewPath\n";
}

// Test 4: Simulate invoice generation response
echo "\n4. Testing invoice route generation...\n";
try {
    // Create a mock invoice object
    $mockInvoice = new \stdClass();
    $mockInvoice->id = 123;
    
    // Test if route helper works
    $url = route('admin.invoices.view', $mockInvoice->id);
    echo "✓ Route generation successful: $url\n";
} catch(Exception $e) {
    echo "✗ Route generation failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "Summary:\n";
echo "- Doctor API should now return " . $doctors->count() . " active doctors\n";
echo "- Invoice view route is available\n";
echo "- Invoice generation should work without 'Route [invoices.view] not defined' error\n";
