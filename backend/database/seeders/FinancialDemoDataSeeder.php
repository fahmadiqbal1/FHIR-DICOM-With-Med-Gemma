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

class FinancialDemoDataSeeder extends Seeder
{
    public function run()
    {
        // First, ensure we have admin and doctor users
        $admin = User::updateOrCreate([
            'email' => 'admin@medgemma.com'
        ], [
            'name' => 'Fahmad Iqbal (Admin)',
            'password' => Hash::make('password'),
            'standard_fee' => 100.00,
            'revenue_percentage' => 35.00
        ]);

        $doctor1 = User::updateOrCreate([
            'email' => 'doctor1@medgemma.com'
        ], [
            'name' => 'Dr. Sarah Johnson',
            'password' => Hash::make('password'),
            'standard_fee' => 1500.00,
            'revenue_percentage' => 70.00
        ]);

        $doctor2 = User::updateOrCreate([
            'email' => 'doctor2@medgemma.com'
        ], [
            'name' => 'Dr. Michael Chen',
            'password' => Hash::make('password'),
            'standard_fee' => 1500.00,
            'revenue_percentage' => 65.00
        ]);

        echo "✅ Created admin and doctor users\n";

        // Create 50 demo patients
        $patients = [];
        for ($i = 1; $i <= 50; $i++) {
            $patient = Patient::updateOrCreate([
                'mrn' => "MRN" . str_pad($i, 6, '0', STR_PAD_LEFT)
            ], [
                'uuid' => Str::uuid(),
                'first_name' => "Patient",
                'last_name' => "Number {$i}",
                'phone' => "555-" . str_pad($i, 3, '0', STR_PAD_LEFT) . "-" . rand(1000, 9999),
                'email' => "patient{$i}@example.com",
                'dob' => Carbon::now()->subYears(rand(20, 70))->format('Y-m-d'),
                'sex' => rand(0, 1) ? 'male' : 'female',
                'address' => "123 Healthcare St, Medical City, MC " . str_pad($i, 5, '0', STR_PAD_LEFT)
            ]);
            $patients[] = $patient;
        }

        echo "✅ Created 50 demo patients\n";

        // Generate invoices and financial data for the last 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day);
            
            // Random number of appointments per day (5-20)
            $appointmentsCount = rand(5, 20);
            
            echo "📅 Generating day {$day} ({$date->format('Y-m-d')}): {$appointmentsCount} appointments\n";
            
            for ($apt = 0; $apt < $appointmentsCount; $apt++) {
                // Randomly assign to doctors
                $doctor = rand(0, 1) ? $doctor1 : $doctor2;
                $patient = $patients[rand(0, count($patients) - 1)];
                
                // Base fee is $1500 with small variations
                $baseFee = 1500;
                $variation = rand(-100, 200); // -100 to +200 variation
                $totalAmount = $baseFee + $variation;
                
                // Create invoice
                $invoice = Invoice::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'amount' => $totalAmount,
                    'status' => 'paid',
                    'service_type' => 'consultation',
                    'description' => 'Medical consultation and examination - $1500 standard fee',
                    'created_at' => $date->copy()->addHours(rand(9, 17))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->copy()->addHours(rand(9, 17))->addMinutes(rand(0, 59))
                ]);
            }
            
            // Generate doctor earnings for this day
            $this->generateDoctorEarnings($doctor1->id, $date->format('Y-m-d'));
            $this->generateDoctorEarnings($doctor2->id, $date->format('Y-m-d'));
            
            // Generate daily revenue summary
            $this->generateDailyRevenueSummary($date->format('Y-m-d'));
            
            // Add some random expenses (30% chance per day)
            if (rand(0, 2) == 0) {
                $categories = ExpenseCategory::all();
                if ($categories->count() > 0) {
                    $expenseAmount = rand(200, 800);
                    DailyExpense::create([
                        'expense_date' => $date->format('Y-m-d'),
                        'category_id' => $categories->random()->id,
                        'description' => 'Daily operational expense - ' . $date->format('M j, Y'),
                        'amount' => $expenseAmount,
                        'recorded_by' => $admin->id
                    ]);
                }
            }
        }

        echo "✅ Generated 30 days of financial data\n";
        echo "💰 Each patient has ~$1500 invoices\n";
        echo "📊 Doctor earnings calculated with revenue sharing\n";
        echo "🏥 Admin dashboard shows complete business overview\n";
        
        // Display summary
        $totalRevenue = Invoice::sum('amount');
        $totalInvoices = Invoice::count();
        $avgInvoice = $totalRevenue / max($totalInvoices, 1);
        
        echo "\n📈 FINANCIAL SUMMARY:\n";
        echo "Total Revenue: $" . number_format($totalRevenue, 2) . "\n";
        echo "Total Invoices: " . number_format($totalInvoices) . "\n";
        echo "Average Invoice: $" . number_format($avgInvoice, 2) . "\n";
        echo "Dr. Sarah (70%): $" . number_format(DoctorEarning::where('doctor_id', $doctor1->id)->sum('doctor_share'), 2) . "\n";
        echo "Dr. Michael (65%): $" . number_format(DoctorEarning::where('doctor_id', $doctor2->id)->sum('doctor_share'), 2) . "\n";
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
        $totalConsultations = $invoices->count();
        $totalRevenue = $invoices->sum('amount');
        $doctorShare = $totalRevenue * ($doctor->revenue_percentage / 100);
        $adminShare = $totalRevenue - $doctorShare;
        
        DoctorEarning::updateOrCreate(
            [
                'doctor_id' => $doctorId,
                'earning_date' => $date
            ],
            [
                'patients_attended' => $totalPatients,
                'total_consultations' => $totalConsultations,
                'doctor_share' => $doctorShare,
                'admin_share' => $adminShare
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
        $totalInvoices = $invoices->count();
        $grossRevenue = $invoices->sum('amount');
        $totalDoctorShares = DoctorEarning::where('earning_date', $date)->sum('doctor_share');
        $totalAdminShares = DoctorEarning::where('earning_date', $date)->sum('admin_share');
        $totalExpenses = DailyExpense::where('expense_date', $date)->sum('amount');
        $netAdminRevenue = $totalAdminShares - $totalExpenses;
        
        DailyRevenueSummary::updateOrCreate(
            ['summary_date' => $date],
            [
                'total_patients' => $totalPatients,
                'total_invoices' => $totalInvoices,
                'total_consultations' => $grossRevenue, // Using this field for total revenue
                'total_lab_fees' => 0,
                'total_imaging_fees' => 0,
                'total_other_fees' => 0,
                'total_doctor_shares' => $totalDoctorShares,
                'total_admin_shares' => $totalAdminShares,
                'gross_revenue' => $grossRevenue,
                'net_admin_revenue' => $netAdminRevenue
            ]
        );
    }
}
