<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Salary;
use App\Models\LabOrder;
use App\Models\ImagingStudy;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they don't exist
        $roles = ['Admin', 'Doctor', 'Lab Technician', 'Radiologist', 'Pharmacist', 'Owner', 'Patient'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create test doctors with revenue sharing
        $doctors = [
            [
                'name' => 'Dr. Sarah Khan',
                'email' => 'sarah.khan@hospital.com',
                'revenue_share' => 60.00
            ],
            [
                'name' => 'Dr. Ahmed Malik', 
                'email' => 'ahmed.malik@hospital.com',
                'revenue_share' => 50.00
            ],
            [
                'name' => 'Dr. Fatima Ali',
                'email' => 'fatima.ali@hospital.com', 
                'revenue_share' => 55.00
            ]
        ];

        foreach ($doctors as $doctorData) {
            $doctor = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'name' => $doctorData['name'],
                    'password' => Hash::make('password'),
                    'revenue_share' => $doctorData['revenue_share']
                ]
            );
            $doctor->assignRole('Doctor');
        }

        // Create lab techs, radiologists, etc.
        $labTech = User::firstOrCreate(
            ['email' => 'lab.tech@hospital.com'],
            [
                'name' => 'Lab Technician',
                'password' => Hash::make('password')
            ]
        );
        $labTech->assignRole('Lab Technician');

        $radiologist = User::firstOrCreate(
            ['email' => 'radiologist@hospital.com'],
            [
                'name' => 'Dr. Radiologist',
                'password' => Hash::make('password'),
                'revenue_share' => 45.00
            ]
        );
        $radiologist->assignRole('Radiologist');

        $admin = User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password')
            ]
        );
        $admin->assignRole('Admin');

        $owner = User::firstOrCreate(
            ['email' => 'owner@hospital.com'],
            [
                'name' => 'Hospital Owner',
                'password' => Hash::make('password')
            ]
        );
        $owner->assignRole('Owner');

        // Create test patients
        $patients = [
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'mrn' => 'MRN001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'dob' => '1985-06-15',
                'sex' => 'male',
                'phone' => '+1-555-0101',
                'email' => 'john.smith@email.com'
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'mrn' => 'MRN002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'dob' => '1992-03-22',
                'sex' => 'female',
                'phone' => '+1-555-0102',
                'email' => 'sarah.johnson@email.com'
            ],
            [
                'uuid' => \Illuminate\Support\Str::uuid(),
                'mrn' => 'MRN003',
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'dob' => '1978-11-08',
                'sex' => 'male',
                'phone' => '+1-555-0103',
                'email' => 'michael.brown@email.com'
            ]
        ];

        foreach ($patients as $patientData) {
            Patient::firstOrCreate(
                ['mrn' => $patientData['mrn']],
                $patientData
            );
        }

        // Create test invoices with revenue splitting
        $this->createTestInvoices();

        // Create test lab orders and imaging studies
        $this->createTestOrders();

        // Create test expenses
        $this->createTestExpenses();

        // Create test salaries
        $this->createTestSalaries();
    }

    private function createTestInvoices()
    {
        $doctors = User::whereHas('roles', function($q) {
            $q->where('name', 'Doctor');
        })->get();
        
        $patients = Patient::all();
        
                // Create invoices for different roles and dates
        $invoiceData = [
            // Today's invoices
            [
                'invoice_number' => 'INV-' . date('Y') . '-001',
                'patient_id' => $patients[0]->id,
                'doctor_id' => $doctors[0]->id,
                'amount' => 10000,
                'issuer_role' => 'admin',
                'description' => 'General consultation',
                'status' => 'paid',
                'created_at' => Carbon::today()
            ],
            [
                'invoice_number' => 'INV-' . date('Y') . '-002',
                'patient_id' => $patients[1]->id,
                'doctor_id' => $doctors[1]->id,
                'amount' => 7500,
                'issuer_role' => 'lab_tech',
                'description' => 'Blood work analysis',
                'status' => 'pending',
                'created_at' => Carbon::today()
            ],
            [
                'invoice_number' => 'INV-' . date('Y') . '-003',
                'patient_id' => $patients[2]->id,
                'doctor_id' => $doctors[2]->id,
                'amount' => 15000,
                'issuer_role' => 'radiologist',
                'description' => 'Chest X-ray',
                'status' => 'paid',
                'created_at' => Carbon::today()
            ],
            // Yesterday's invoices
            [
                'invoice_number' => 'INV-' . date('Y') . '-004',
                'patient_id' => $patients[0]->id,
                'doctor_id' => $doctors[0]->id,
                'amount' => 5000,
                'issuer_role' => 'pharmacist',
                'description' => 'Prescription medications',
                'status' => 'paid',
                'created_at' => Carbon::yesterday()
            ]
        ];

        foreach ($invoiceData as $data) {
            Invoice::create($data);
        }
    }

    private function createTestOrders()
    {
        $doctors = User::whereHas('roles', function($q) {
            $q->where('name', 'Doctor');
        })->get();
        
        $patients = Patient::all();

        // Create lab orders (if table exists)
        try {
            $labOrders = [
                [
                    'patient_id' => $patients[0]->id,
                    'doctor_id' => $doctors[0]->id,
                    'test_name' => 'Complete Blood Count',
                    'status' => 'pending',
                    'priority' => 'routine'
                ],
                [
                    'patient_id' => $patients[1]->id,
                    'doctor_id' => $doctors[1]->id,
                    'test_name' => 'Lipid Panel',
                    'status' => 'completed',
                    'priority' => 'urgent'
                ]
            ];

            foreach ($labOrders as $order) {
                LabOrder::create($order);
            }
        } catch (\Exception $e) {
            // Lab orders table doesn't exist yet
        }

        // Create imaging studies (if table exists)
        try {
            $imagingStudies = [
                [
                    'patient_id' => $patients[0]->id,
                    'doctor_id' => $doctors[0]->id,
                    'description' => 'Chest X-ray',
                    'modality' => 'X-ray',
                    'status' => 'pending',
                    'priority' => 'routine'
                ],
                [
                    'patient_id' => $patients[1]->id,
                    'doctor_id' => $doctors[1]->id,
                    'description' => 'Brain MRI',
                    'modality' => 'MRI',
                    'status' => 'completed',
                    'priority' => 'urgent'
                ]
            ];

            foreach ($imagingStudies as $study) {
                ImagingStudy::create($study);
            }
        } catch (\Exception $e) {
            // Imaging studies table doesn't exist yet
        }
    }

    private function createTestExpenses()
    {
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'Admin');
        })->first();

        $expenses = [
            [
                'title' => 'Office Supplies',
                'description' => 'Stationery and printing materials',
                'amount' => 2500,
                'category' => 'office_supplies',
                'created_by' => $admin->id,
                'expense_date' => Carbon::today()
            ],
            [
                'title' => 'Equipment Maintenance',
                'description' => 'Lab equipment servicing',
                'amount' => 15000,
                'category' => 'maintenance',
                'created_by' => $admin->id,
                'expense_date' => Carbon::yesterday()
            ]
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }

    private function createTestSalaries()
    {
        $staff = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Lab Technician', 'Radiologist']);
        })->get();

        foreach ($staff as $member) {
            Salary::create([
                'staff_id' => $member->id,
                'designation' => $member->roles->first()->name,
                'base_salary' => 50000,
                'bonus' => 5000,
                'deductions' => 2000,
                'paid_on' => Carbon::now()->startOfMonth(),
                'status' => 'paid',
                'notes' => 'Monthly salary for ' . Carbon::now()->format('F Y')
            ]);
        }
    }
}
