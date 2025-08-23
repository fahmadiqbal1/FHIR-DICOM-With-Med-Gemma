<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\API\DashboardController;
use Illuminate\Http\Request;

echo "Testing Dashboard APIs...\n\n";

$controller = new DashboardController();

// Test Admin Stats
echo "=== ADMIN DASHBOARD ===\n";
try {
    $adminStats = $controller->adminStats();
    $data = $adminStats->getData();
    
    echo "Total Income: $" . number_format($data->total_income / 100, 2) . "\n";
    echo "Total Invoices: " . $data->total_invoices . "\n";
    echo "Owner Share: $" . number_format($data->owner_share / 100, 2) . "\n";
    echo "Doctors Share: $" . number_format($data->doctors_share / 100, 2) . "\n";
    echo "New Patients: " . $data->new_patients . "\n";
    echo "Date: " . $data->date . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== OWNER DASHBOARD ===\n";
try {
    $ownerStats = $controller->ownerStats();
    $data = $ownerStats->getData();
    
    echo "Income Today: $" . number_format($data->income_today / 100, 2) . "\n";
    echo "Owner Share: $" . number_format($data->owner_share / 100, 2) . "\n";
    echo "Doctors Share: $" . number_format($data->doctors_share / 100, 2) . "\n";
    echo "Expenses Today: $" . number_format($data->expenses_today / 100, 2) . "\n";
    echo "Staff Salaries: $" . number_format($data->staff_salaries / 100, 2) . "\n";
    echo "Profit Today: $" . number_format($data->profit_today / 100, 2) . "\n";
    echo "Monthly Income: $" . number_format($data->monthly_income / 100, 2) . "\n";
    echo "Monthly Profit: $" . number_format($data->monthly_profit / 100, 2) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDashboard API tests completed!\n";
