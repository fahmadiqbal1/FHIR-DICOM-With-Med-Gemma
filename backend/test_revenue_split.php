<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test revenue splitting
echo "Testing Revenue Splitting Logic...\n\n";

// Create a test doctor with revenue share
$doctor = \App\Models\User::create([
    'name' => 'Test Doctor',
    'email' => 'test.doctor@test.com',
    'password' => bcrypt('password'),
    'revenue_share' => 60,
    'is_active_doctor' => 1
]);

echo "Created doctor: {$doctor->name} with {$doctor->revenue_share}% revenue share\n";

// Create a test patient
$patient = \App\Models\Patient::create([
    'uuid' => \Illuminate\Support\Str::uuid(),
    'mrn' => 'TEST001',
    'first_name' => 'Test',
    'last_name' => 'Patient',
    'dob' => '1990-01-01',
    'sex' => 'male',
    'phone' => '+1-555-0000',
    'email' => 'test.patient@test.com'
]);

echo "Created patient: {$patient->first_name} {$patient->last_name}\n";

// Create a test invoice
$invoice = \App\Models\Invoice::create([
    'invoice_number' => 'TEST-' . time(),
    'patient_id' => $patient->id,
    'doctor_id' => $doctor->id,
    'amount' => 10000, // $100.00
    'service_type' => 'Consultation',
    'description' => 'Test consultation',
    'issuer_role' => 'admin'
]);

echo "Created invoice: {$invoice->invoice_number} for \${$invoice->amount}\n";
echo "Doctor share: \${$invoice->doctor_share}\n";
echo "Owner share: \${$invoice->owner_share}\n";
echo "Revenue split working: " . ($invoice->doctor_share > 0 ? 'YES' : 'NO') . "\n";

// Clean up
$invoice->delete();
$patient->delete();
$doctor->delete();

echo "\nTest completed and cleaned up.\n";
