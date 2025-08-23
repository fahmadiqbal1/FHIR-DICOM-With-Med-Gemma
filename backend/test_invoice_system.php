<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Invoice System...\n";

// Test 1: Can we fetch doctors?
echo "1. Testing doctors API...\n";
try {
    $doctors = App\Models\User::select('id', 'name', 'email')
        ->whereIn('role', ['doctor', 'Doctor'])
        ->limit(3)
        ->get();
    
    foreach ($doctors as $doctor) {
        echo "   - Doctor ID: {$doctor->id}, Name: {$doctor->name}, Email: {$doctor->email}\n";
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

// Test 2: Can we fetch patients?
echo "\n2. Testing patients...\n";
try {
    $patients = App\Models\Patient::limit(2)->get();
    foreach ($patients as $patient) {
        echo "   - Patient ID: {$patient->id}, Name: {$patient->first_name} {$patient->last_name}\n";
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

// Test 3: Can we create an invoice?
echo "\n3. Testing invoice creation...\n";
try {
    $patient = App\Models\Patient::first();
    $doctor = App\Models\User::whereIn('role', ['doctor', 'Doctor'])->first();
    
    if ($patient && $doctor) {
        $invoice = App\Models\Invoice::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'service_type' => 'Test consultation',
            'amount' => 50.00,
            'description' => 'Test invoice creation',
            'status' => 'pending'
        ]);
        
        echo "   SUCCESS: Invoice created with ID: {$invoice->id}\n";
        echo "   Invoice Number: {$invoice->invoice_number}\n";
        echo "   Amount: \${$invoice->amount}\n";
        
        // Delete the test invoice
        $invoice->delete();
        echo "   Test invoice cleaned up.\n";
    } else {
        echo "   ERROR: No patient or doctor found in database.\n";
    }
} catch (Exception $e) {
    echo "   ERROR: " . $e->getMessage() . "\n";
}

echo "\nTesting completed.\n";
