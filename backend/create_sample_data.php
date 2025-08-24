<?php

require_once 'vendor/autoload.php';

use App\Models\Patient;
use App\Models\Invoice;
use App\Models\DoctorEarning;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Creating sample patients and financial data...\n";

// Sample patients data
$patients = [
    ['name' => 'John Smith', 'mrn' => 'MRN001', 'dob' => '1985-03-15', 'sex' => 'male', 'phone' => '555-0101'],
    ['name' => 'Sarah Johnson', 'mrn' => 'MRN002', 'dob' => '1992-07-22', 'sex' => 'female', 'phone' => '555-0102'],
    ['name' => 'Michael Brown', 'mrn' => 'MRN003', 'dob' => '1978-11-08', 'sex' => 'male', 'phone' => '555-0103'],
    ['name' => 'Emily Davis', 'mrn' => 'MRN004', 'dob' => '1990-05-14', 'sex' => 'female', 'phone' => '555-0104'],
    ['name' => 'David Wilson', 'mrn' => 'MRN005', 'dob' => '1982-09-30', 'sex' => 'male', 'phone' => '555-0105'],
    ['name' => 'Lisa Anderson', 'mrn' => 'MRN006', 'dob' => '1995-12-03', 'sex' => 'female', 'phone' => '555-0106'],
    ['name' => 'Robert Miller', 'mrn' => 'MRN007', 'dob' => '1976-04-18', 'sex' => 'male', 'phone' => '555-0107'],
    ['name' => 'Jennifer Garcia', 'mrn' => 'MRN008', 'dob' => '1988-08-25', 'sex' => 'female', 'phone' => '555-0108'],
    ['name' => 'Christopher Martinez', 'mrn' => 'MRN009', 'dob' => '1980-01-12', 'sex' => 'male', 'phone' => '555-0109'],
    ['name' => 'Amanda Rodriguez', 'mrn' => 'MRN010', 'dob' => '1993-06-07', 'sex' => 'female', 'phone' => '555-0110'],
];

// Create patients
foreach ($patients as $patientData) {
    $patient = Patient::firstOrCreate(
        ['mrn' => $patientData['mrn']],
        [
            'uuid' => Str::uuid(),
            'name' => $patientData['name'],
            'first_name' => explode(' ', $patientData['name'])[0],
            'last_name' => explode(' ', $patientData['name'])[1] ?? '',
            'dob' => $patientData['dob'],
            'sex' => $patientData['sex'],
            'phone' => $patientData['phone'],
            'email' => strtolower(str_replace(' ', '.', $patientData['name'])) . '@example.com',
            'address' => '123 Main St, City, State 12345'
        ]
    );
    echo "Created/found patient: " . $patient->name . "\n";
}

// Get doctors for invoices
$doctors = User::where(function($query) {
    $query->where('role', 'doctor')
          ->orWhereHas('roles', function($roleQuery) {
              $roleQuery->where('name', 'Doctor');
          });
})->get();

if ($doctors->isEmpty()) {
    echo "No doctors found, creating sample doctor...\n";
    $doctor = User::create([
        'name' => 'Dr. Sample Doctor',
        'email' => 'sample.doctor@medgemma.com',
        'password' => Hash::make('password'),
        'role' => 'doctor',
        'email_verified_at' => now(),
        'is_active_doctor' => 1,
        'revenue_share' => 70
    ]);
    $doctors = collect([$doctor]);
}

// Service types and prices
$services = [
    'Consultation' => ['min' => 150, 'max' => 300],
    'Follow-up Visit' => ['min' => 100, 'max' => 200],
    'Laboratory Test - CBC' => ['min' => 80, 'max' => 120],
    'Laboratory Test - Lipid Panel' => ['min' => 90, 'max' => 140],
    'X-Ray Chest' => ['min' => 200, 'max' => 350],
    'CT Scan' => ['min' => 800, 'max' => 1200],
    'MRI' => ['min' => 1200, 'max' => 2000],
    'Ultrasound' => ['min' => 300, 'max' => 500],
    'Prescription Medication' => ['min' => 50, 'max' => 200],
    'Blood Pressure Check' => ['min' => 40, 'max' => 80],
    'ECG' => ['min' => 120, 'max' => 200],
    'Immunization' => ['min' => 60, 'max' => 150],
];

$invoiceStatuses = ['paid', 'pending', 'overdue'];

// Create invoices for the last 2 months
$patients = Patient::all();
$invoiceCount = Invoice::count(); // Start from existing count
$dailyEarnings = []; // Track earnings by doctor and date

for ($month = 0; $month < 2; $month++) {
    foreach ($patients as $patient) {
        // Create 2-4 invoices per patient per month
        $invoicesThisMonth = rand(2, 4);
        
        for ($i = 0; $i < $invoicesThisMonth; $i++) {
            $doctor = $doctors->random();
            $service = array_rand($services);
            $serviceData = $services[$service];
            
            $amount = rand($serviceData['min'], $serviceData['max']);
            $createdDate = Carbon::now()->subMonths($month)->subDays(rand(1, 28));
            $dueDate = $createdDate->copy()->addDays(30);
            $dateKey = $createdDate->format('Y-m-d');
            
            $invoiceCount++;
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . str_pad($invoiceCount, 6, '0', STR_PAD_LEFT),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'amount' => $amount,
                'due_date' => $dueDate,
                'status' => $invoiceStatuses[array_rand($invoiceStatuses)],
                'description' => $service,
                'created_at' => $createdDate,
                'updated_at' => $createdDate,
            ]);
            
            // Aggregate earnings by doctor and date
            $key = $doctor->id . '_' . $dateKey;
            if (!isset($dailyEarnings[$key])) {
                $dailyEarnings[$key] = [
                    'doctor_id' => $doctor->id,
                    'earning_date' => $dateKey,
                    'patients_attended' => 0,
                    'total_consultations' => 0,
                    'doctor_share' => 0,
                    'admin_share' => 0,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ];
            }
            
            $doctorShare = $amount * ($doctor->revenue_share / 100);
            $dailyEarnings[$key]['patients_attended']++;
            $dailyEarnings[$key]['total_consultations'] += $amount;
            $dailyEarnings[$key]['doctor_share'] += $doctorShare;
            $dailyEarnings[$key]['admin_share'] += $amount - $doctorShare;
        }
    }
}

// Create aggregated doctor earnings
foreach ($dailyEarnings as $earning) {
    DoctorEarning::updateOrCreate(
        [
            'doctor_id' => $earning['doctor_id'],
            'earning_date' => $earning['earning_date']
        ],
        $earning
    );
}

echo "\nSample data creation completed!\n";
echo "Created {$invoiceCount} invoices and corresponding earning records.\n";
echo "Created " . $patients->count() . " patients.\n";
echo "Data spans the last 2 months for owner dashboard analytics.\n";
