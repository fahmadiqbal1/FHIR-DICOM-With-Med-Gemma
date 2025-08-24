<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\ImagingStudy;
use App\Models\Patient;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Creating sample imaging studies...\n";

// Get first few patients
$patients = Patient::take(3)->get();

if ($patients->count() == 0) {
    echo "No patients found. Creating sample patients first...\n";
    
    // Create sample patients
    $samplePatients = [
        ['name' => 'John Doe', 'email' => 'john.doe@email.com', 'mrn' => 'MRN001'],
        ['name' => 'Jane Smith', 'email' => 'jane.smith@email.com', 'mrn' => 'MRN002'],
        ['name' => 'Bob Johnson', 'email' => 'bob.johnson@email.com', 'mrn' => 'MRN003']
    ];
    
    foreach ($samplePatients as $patientData) {
        Patient::create($patientData);
    }
    
    $patients = Patient::take(3)->get();
}

// Create sample imaging studies
$studies = [
    [
        'patient_id' => $patients[0]->id,
        'description' => 'Chest CT with contrast',
        'modality' => 'CT',
        'study_date' => now()->subDays(1),
        'status' => 'pending',
        'urgency' => 'high',
        'created_by' => null
    ],
    [
        'patient_id' => $patients[1]->id,
        'description' => 'Brain MRI',
        'modality' => 'MRI', 
        'study_date' => now()->subDays(2),
        'status' => 'in-progress',
        'urgency' => 'normal',
        'created_by' => null
    ],
    [
        'patient_id' => $patients[2]->id,
        'description' => 'Abdominal Ultrasound',
        'modality' => 'US',
        'study_date' => now()->subHours(6),
        'status' => 'pending',
        'urgency' => 'normal',
        'created_by' => null
    ]
];

foreach ($studies as $studyData) {
    ImagingStudy::create($studyData);
    echo "Created study: " . $studyData['description'] . "\n";
}

echo "Sample data creation completed!\n";
