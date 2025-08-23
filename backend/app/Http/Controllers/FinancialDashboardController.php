<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorEarning;
use App\Models\DailyRevenueSummary;
use App\Models\DailyExpense;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialDashboardController extends Controller
{
    public function doctorDashboard(Request $request)
    {
        $doctorId = Auth::id();
        $date = $request->get('date', Carbon::today()->toDateString());
        
        // Generate demo data for doctor financial dashboard (Check-ups only)
        $revenuePercentage = 70; // Doctor gets 70% of check-up consultations
        
        // Demo data for today's check-up consultations
        $todayConsultations = $this->getTodayConsultationCount($date);
        $todayPatients = $todayConsultations; // Assuming 1 consultation per patient
        $checkupFee = 120; // Base check-up fee
        $totalRevenue = $todayConsultations * $checkupFee;
        $todayEarnings = $totalRevenue * ($revenuePercentage / 100);
        
        // Demo data for weekly earnings (check-ups only)
        $weeklyConsultations = $this->getWeeklyConsultationCount($date);
        $weeklyRevenue = $weeklyConsultations * $checkupFee;
        $weeklyEarnings = $weeklyRevenue * ($revenuePercentage / 100);
        
        // Demo data for monthly earnings (check-ups only)
        $monthlyConsultations = $this->getMonthlyConsultationCount($date);
        $monthlyRevenue = $monthlyConsultations * $checkupFee;
        $monthlyEarnings = $monthlyRevenue * ($revenuePercentage / 100);
        
        // Calculate performance metrics
        $avgPerPatient = $todayPatients > 0 ? $todayEarnings / $todayPatients : 0;
        $patientSatisfaction = 94; // Demo satisfaction score
        $efficiency = 88; // Demo efficiency score
        
        // Generate chart data for last 7 days
        $chartData = $this->generateChartData($date, $checkupFee, $revenuePercentage);
        
        // Generate recent consultations demo data
        $recentConsultations = $this->generateRecentConsultations($date, $checkupFee, $revenuePercentage);
        
        return view('doctor-financial-dashboard', compact(
            'todayEarnings',
            'todayConsultations',
            'todayPatients',
            'weeklyEarnings',
            'weeklyConsultations',
            'monthlyEarnings',
            'monthlyConsultations',
            'revenuePercentage',
            'avgPerPatient',
            'patientSatisfaction',
            'efficiency',
            'totalRevenue',
            'chartData',
            'recentConsultations',
            'date'
        ));
    }
    
    private function getTodayConsultationCount($date)
    {
        // Demo data - varies by day of week
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $baseCounts = [1, 8, 12, 15, 18, 14, 6]; // Sun-Sat
        return $baseCounts[$dayOfWeek] + rand(-2, 3);
    }
    
    private function getWeeklyConsultationCount($date)
    {
        // Demo data - sum for the week
        $total = 0;
        $weekStart = Carbon::parse($date)->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $dayDate = $weekStart->copy()->addDays($i);
            if ($dayDate->lte(Carbon::parse($date))) {
                $total += $this->getTodayConsultationCount($dayDate->toDateString());
            }
        }
        return $total;
    }
    
    private function getMonthlyConsultationCount($date)
    {
        // Demo data - estimate for the month
        $dayOfMonth = Carbon::parse($date)->day;
        $averagePerDay = 12;
        return $dayOfMonth * $averagePerDay + rand(-20, 30);
    }
    
    private function generateChartData($date, $checkupFee, $revenuePercentage)
    {
        $labels = [];
        $values = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $chartDate = Carbon::parse($date)->subDays($i);
            $consultations = $this->getTodayConsultationCount($chartDate->toDateString());
            $earnings = $consultations * $checkupFee * ($revenuePercentage / 100);
            
            $labels[] = $chartDate->format('M j');
            $values[] = round($earnings, 2);
        }
        
        return [
            'labels' => $labels,
            'values' => $values
        ];
    }
    
    private function generateRecentConsultations($date, $checkupFee, $revenuePercentage)
    {
        $consultations = [];
        $patientNames = [
            'Sarah Johnson', 'Michael Chen', 'Emily Davis', 'James Wilson',
            'Maria Garcia', 'David Brown', 'Lisa Anderson', 'Robert Taylor',
            'Jennifer Lee', 'Christopher Martin', 'Amanda White', 'Daniel Garcia'
        ];
        
        $consultationCount = $this->getTodayConsultationCount($date);
        
        for ($i = 0; $i < min($consultationCount, 8); $i++) {
            $time = Carbon::parse($date)->setHour(9 + $i)->setMinute(rand(0, 45));
            $duration = rand(15, 30) . ' min';
            $doctorFee = $checkupFee * ($revenuePercentage / 100);
            
            $consultations[] = [
                'patient_name' => $patientNames[array_rand($patientNames)],
                'service' => 'General Check-up',
                'time' => $time->format('g:i A'),
                'duration' => $duration,
                'doctor_fee' => $doctorFee,
                'total_fee' => $checkupFee
            ];
        }
        
        return array_reverse($consultations); // Most recent first
    }
    
    public function adminDashboard(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $period = $request->get('period', 'day'); // day, week, month, year
        
        // Calculate date ranges based on period
        switch ($period) {
            case 'week':
                $startDate = Carbon::parse($date)->startOfWeek();
                $endDate = Carbon::parse($date)->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::parse($date)->startOfMonth();
                $endDate = Carbon::parse($date)->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::parse($date)->startOfYear();
                $endDate = Carbon::parse($date)->endOfYear();
                break;
            default:
                $startDate = $endDate = Carbon::parse($date);
        }
        
        // Get total revenue for the period
        $totalRevenue = Invoice::whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        
        // Get total doctor earnings for the period
        $totalDoctorEarnings = DoctorEarning::whereBetween('earning_date', [$startDate, $endDate])
            ->sum('doctor_share');
        
        // Get total expenses for the period
        $totalExpenses = DailyExpense::whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');
        
        // Calculate admin earnings (total revenue - doctor earnings)
        $adminEarnings = $totalRevenue - $totalDoctorEarnings;
        
        // Net profit (admin earnings - expenses)
        $netProfit = $adminEarnings - $totalExpenses;
        
        // Get top performing doctors
        $topDoctors = DoctorEarning::select('doctor_id', DB::raw('SUM(doctor_share) as total'))
            ->with('doctor:id,name')
            ->whereBetween('earning_date', [$startDate, $endDate])
            ->groupBy('doctor_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        // Get daily revenue trend for charts
        $dailyRevenue = Invoice::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as appointments')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        
        // Get expense breakdown by category
        $expenseBreakdown = DailyExpense::select('category_id', DB::raw('SUM(amount) as total'))
            ->with('category:id,name')
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->groupBy('category_id')
            ->get();
        
        // Get recent invoices
        $recentInvoices = Invoice::with(['patient:id,first_name,last_name', 'doctor:id,name'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
        
        return redirect('/dashboard')->with('financial_data', compact(
            'totalRevenue',
            'totalDoctorEarnings',
            'totalExpenses',
            'adminEarnings',
            'netProfit',
            'topDoctors',
            'dailyRevenue',
            'expenseBreakdown',
            'recentInvoices',
            'date',
            'period',
            'startDate',
            'endDate'
        ));
    }
    
    public function generateDoctorEarning($doctorId, $date)
    {
        // Get all invoices for the doctor on the specified date
        $invoices = Invoice::where('doctor_id', $doctorId)
            ->whereDate('created_at', $date)
            ->get();
        
        if ($invoices->isEmpty()) {
            return null;
        }
        
        $doctor = User::find($doctorId);
        $revenuePercentage = $doctor->revenue_percentage ?? 70;
        
        $totalPatients = $invoices->unique('patient_id')->count();
        $totalAppointments = $invoices->count();
        $totalRevenue = $invoices->sum('total_amount');
        $doctorEarnings = $totalRevenue * ($revenuePercentage / 100);
        
        return DoctorEarning::updateOrCreate(
            [
                'doctor_id' => $doctorId,
                'date' => $date
            ],
            [
                'total_patients' => $totalPatients,
                'total_appointments' => $totalAppointments,
                'total_revenue' => $totalRevenue,
                'doctor_percentage' => $revenuePercentage,
                'total_earnings' => $doctorEarnings
            ]
        );
    }
    
    public function generateDailyRevenueSummary($date)
    {
        // Get all invoices for the date
        $invoices = Invoice::whereDate('created_at', $date)->get();
        
        if ($invoices->isEmpty()) {
            return null;
        }
        
        $totalPatients = $invoices->unique('patient_id')->count();
        $totalAppointments = $invoices->count();
        $totalRevenue = $invoices->sum('total_amount');
        
        // Calculate total doctor earnings for the day
        $totalDoctorEarnings = DoctorEarning::where('date', $date)->sum('total_earnings');
        
        // Get total expenses for the day
        $totalExpenses = DailyExpense::where('expense_date', $date)->sum('amount');
        
        // Calculate admin revenue
        $adminRevenue = $totalRevenue - $totalDoctorEarnings;
        $netProfit = $adminRevenue - $totalExpenses;
        
        return DailyRevenueSummary::updateOrCreate(
            ['date' => $date],
            [
                'total_patients' => $totalPatients,
                'total_appointments' => $totalAppointments,
                'total_revenue' => $totalRevenue,
                'total_doctor_earnings' => $totalDoctorEarnings,
                'admin_revenue' => $adminRevenue,
                'total_expenses' => $totalExpenses,
                'net_profit' => $netProfit
            ]
        );
    }
}
