<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\DoctorEarning;
use App\Models\DailyRevenueSummary;
use App\Models\DailyExpense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FinancialDemoSeeder extends Seeder
{
    public function run()
    {
        // Create demo users if they don't exist
        $admin = User::firstOrCreate([
            'email' => 'admin@medgemma.com'
        ], [
            'name' => 'Fahmad Iqbal',
            'password' => Hash::make('password'),
            'standard_fee' => 100.00,
            'revenue_percentage' => 35.00
        ]);

        $doctor1 = User::firstOrCreate([
            'email' => 'doctor1@medgemma.com'
        ], [
            'name' => 'Dr. Sarah Johnson',
            'password' => Hash::make('password'),
            'standard_fee' => 150.00,
            'revenue_percentage' => 70.00
        ]);

        $doctor2 = User::firstOrCreate([
            'email' => 'doctor2@medgemma.com'
        ], [
            'name' => 'Dr. Michael Chen',
            'password' => Hash::make('password'),
            'standard_fee' => 180.00,
            'revenue_percentage' => 65.00
        ]);

        // Create demo patients
        $patients = [];
        for ($i = 1; $i <= 20; $i++) {
            $patients[] = Patient::firstOrCreate([
                'email' => "patient{$i}@example.com"
            ], [
                'first_name' => "Patient",
                'last_name' => "Number {$i}",
                'phone' => "555-000-{$i}",
                'dob' => Carbon::now()->subYears(rand(20, 70))->format('Y-m-d'),
                'sex' => rand(0, 1) ? 'male' : 'female',
                'address' => "123 Main St, City {$i}",
                'uuid' => Str::uuid(),
                'mrn' => "MRN" . str_pad($i, 6, '0', STR_PAD_LEFT)
            ]);
        }

        // Generate demo data for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day);
            
            // Random number of appointments per day (0-15)
            $appointmentsCount = rand(0, 15);
            
            for ($apt = 0; $apt < $appointmentsCount; $apt++) {
                $doctor = rand(0, 1) ? $doctor1 : $doctor2;
                $patient = $patients[rand(0, count($patients) - 1)];
                $fee = $doctor->standard_fee + rand(-20, 50); // Some variation in fees
                
                // Create invoice
                $invoice = Invoice::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'amount' => $fee,
                    'status' => 'paid',
                    'service_type' => 'consultation',
                    'description' => 'Medical consultation',
                    'created_at' => $date->copy()->addHours(rand(9, 17))->addMinutes(rand(0, 59))
                ]);
            }
            
            // Generate doctor earnings for this day
            if ($appointmentsCount > 0) {
                $this->generateDoctorEarnings($doctor1->id, $date->format('Y-m-d'));
                $this->generateDoctorEarnings($doctor2->id, $date->format('Y-m-d'));
                
                // Generate daily revenue summary
                $this->generateDailyRevenueSummary($date->format('Y-m-d'));
            }
            
            // Add some random expenses
            if (rand(0, 2) == 0) { // 33% chance of expense on any given day
                $categories = ExpenseCategory::all();
                if ($categories->count() > 0) {
                    DailyExpense::create([
                        'expense_date' => $date->format('Y-m-d'),
                        'category_id' => $categories->random()->id,
                        'description' => 'Sample expense for ' . $date->format('M j'),
                        'amount' => rand(50, 500),
                        'recorded_by' => $admin->id
                    ]);
                }
            }
        }
    }
    
    private function generateDoctorEarnings($doctorId, $date)
    {
        $invoices = Invoice::where('doctor_id', $doctorId)
            ->whereDate('created_at', $date)
            ->get();
        
        if ($invoices->isEmpty()) {
            return;
        }
        
        $doctor = User::find($doctorId);
        $totalPatients = $invoices->unique('patient_id')->count();
        $totalAppointments = $invoices->count();
        $totalRevenue = $invoices->sum('amount');
        $doctorEarnings = $totalRevenue * ($doctor->revenue_percentage / 100);
        
        DoctorEarning::updateOrCreate(
            [
                'doctor_id' => $doctorId,
                'earning_date' => $date
            ],
            [
                'patients_attended' => $totalPatients,
                'total_consultations' => $totalAppointments,
                'doctor_share' => $doctorEarnings,
                'admin_share' => $totalRevenue - $doctorEarnings
            ]
        );
    }
    
    private function generateDailyRevenueSummary($date)
    {
        $invoices = Invoice::whereDate('created_at', $date)->get();
        
        if ($invoices->isEmpty()) {
            return;
        }
        
        $totalPatients = $invoices->unique('patient_id')->count();
        $totalAppointments = $invoices->count();
        $totalRevenue = $invoices->sum('amount');
        $totalDoctorEarnings = DoctorEarning::where('earning_date', $date)->sum('doctor_share');
        $totalExpenses = DailyExpense::where('expense_date', $date)->sum('amount');
        $adminRevenue = $totalRevenue - $totalDoctorEarnings;
        $netProfit = $adminRevenue - $totalExpenses;
        
        DailyRevenueSummary::updateOrCreate(
            ['summary_date' => $date],
            [
                'total_patients' => $totalPatients,
                'total_invoices' => $totalAppointments,
                'total_consultations' => $totalRevenue,
                'total_lab_fees' => 0,
                'total_imaging_fees' => 0,
                'total_other_fees' => 0,
                'total_doctor_shares' => $totalDoctorEarnings,
                'total_admin_shares' => $adminRevenue,
                'gross_revenue' => $totalRevenue,
                'net_admin_revenue' => $netProfit
            ]
        );
    }
}
