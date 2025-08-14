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
        
        // Get today's earnings for the doctor
        $todayEarnings = DoctorEarning::where('doctor_id', $doctorId)
            ->where('earning_date', $date)
            ->first();
        
        // Get patient count for today
        $todayPatients = $todayEarnings ? $todayEarnings->patients_attended : 0;
        
        // Get total appointments for today
        $todayAppointments = $todayEarnings ? $todayEarnings->total_consultations : 0;
        
        // Get weekly earnings
        $weekStart = Carbon::parse($date)->startOfWeek();
        $weekEnd = Carbon::parse($date)->endOfWeek();
        
        $weeklyEarnings = DoctorEarning::where('doctor_id', $doctorId)
            ->whereBetween('earning_date', [$weekStart, $weekEnd])
            ->sum('doctor_share');
        
        // Get monthly earnings
        $monthStart = Carbon::parse($date)->startOfMonth();
        $monthEnd = Carbon::parse($date)->endOfMonth();
        
        $monthlyEarnings = DoctorEarning::where('doctor_id', $doctorId)
            ->whereBetween('earning_date', [$monthStart, $monthEnd])
            ->sum('doctor_share');
        
        // Get recent daily earnings for chart (last 7 days)
        $recentEarnings = DoctorEarning::where('doctor_id', $doctorId)
            ->whereBetween('earning_date', [Carbon::parse($date)->subDays(6), $date])
            ->orderBy('earning_date')
            ->get();
        
        // Get doctor's revenue percentage
        $doctor = User::find($doctorId);
        $revenuePercentage = $doctor->revenue_percentage ?? 70;
        
        return view('financial.doctor-dashboard', compact(
            'todayEarnings',
            'todayPatients', 
            'todayAppointments',
            'weeklyEarnings',
            'monthlyEarnings',
            'recentEarnings',
            'revenuePercentage',
            'date'
        ));
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
        
        return view('financial.admin-dashboard', compact(
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
