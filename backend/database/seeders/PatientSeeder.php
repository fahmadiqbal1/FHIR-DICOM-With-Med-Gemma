<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing patients to avoid encryption key conflicts
        Patient::truncate();

        $patients = [
            [
                'mrn' => 'MRN001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'dob' => '1985-06-15',
                'sex' => 'male',
                'phone' => '+1-555-0101',
                'email' => 'john.doe@email.com',
                'address' => '123 Main St, Anytown, USA'
            ],
            [
                'mrn' => 'MRN002',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'dob' => '1990-03-22',
                'sex' => 'female',
                'phone' => '+1-555-0102',
                'email' => 'jane.smith@email.com',
                'address' => '456 Oak Ave, Somewhere, USA'
            ],
            [
                'mrn' => 'MRN003',
                'first_name' => 'Michael',
                'last_name' => 'Johnson',
                'dob' => '1978-11-08',
                'sex' => 'male',
                'phone' => '+1-555-0103',
                'email' => 'michael.johnson@email.com',
                'address' => '789 Pine Rd, Elsewhere, USA'
            ],
            [
                'mrn' => 'MRN004',
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'dob' => '1982-07-30',
                'sex' => 'female',
                'phone' => '+1-555-0104',
                'email' => 'sarah.williams@email.com',
                'address' => '321 Elm St, Nowhere, USA'
            ],
            [
                'mrn' => 'MRN005',
                'first_name' => 'David',
                'last_name' => 'Brown',
                'dob' => '1975-12-12',
                'sex' => 'male',
                'phone' => '+1-555-0105',
                'email' => 'david.brown@email.com',
                'address' => '654 Maple Dr, Anywhere, USA'
            ],
            [
                'mrn' => 'MRN006',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'dob' => '1988-09-18',
                'sex' => 'female',
                'phone' => '+1-555-0106',
                'email' => 'emily.davis@email.com',
                'address' => '987 Cedar Ln, Someplace, USA'
            ],
            [
                'mrn' => 'MRN007',
                'first_name' => 'Robert',
                'last_name' => 'Miller',
                'dob' => '1970-04-25',
                'sex' => 'male',
                'phone' => '+1-555-0107',
                'email' => 'robert.miller@email.com',
                'address' => '147 Birch St, Everytown, USA'
            ],
            [
                'mrn' => 'MRN008',
                'first_name' => 'Jessica',
                'last_name' => 'Wilson',
                'dob' => '1993-01-14',
                'sex' => 'female',
                'phone' => '+1-555-0108',
                'email' => 'jessica.wilson@email.com',
                'address' => '258 Spruce Ave, Hometown, USA'
            ]
        ];

        foreach ($patients as $patientData) {
            $patientData['uuid'] = Str::uuid();
            $patientData['created_at'] = Carbon::now();
            $patientData['updated_at'] = Carbon::now();
            
            Patient::create($patientData);
        }

        $this->command->info('Created ' . count($patients) . ' test patients with proper encryption.');
    }
}
