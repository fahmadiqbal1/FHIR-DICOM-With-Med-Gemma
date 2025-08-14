<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\ImagingStudy;
use App\Models\LabOrder;
use App\Models\AiResult;
use App\Models\User;
use App\Models\DoctorEarning;
use App\Models\Invoice;
use App\Models\DailyRevenueSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    /**
     * Show the dashboard page
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Show the patients page
     */
    public function patients()
    {
        return view('patients');
    }

    /**
     * Show the clean patients page
     */
    public function patientsClean()
    {
        return view('patients-clean');
    }

    /**
     * Show the medgemma page
     */
    public function medgemma()
    {
        return view('medgemma');
    }

    /**
     * Show the reports page
     */
    public function reports()
    {
        return view('reports');
    }

    /**
     * Show the help page
     */
    public function help()
    {
        return view('help');
    }

    /**
     * Show the DICOM upload page
     */
    public function dicomUpload()
    {
        return view('dicom-upload');
    }

    /**
     * Show the admin management page
     */
    public function adminManagement()
    {
        return view('admin-audit-logs');
    }

    /**
     * Show the user management page
     */
    public function userManagement()
    {
        return view('user-management');
    }

    /**
     * Show the financial expense form page
     */
    public function financialExpenseForm()
    {
        return view('financial.expense-form');
    }

    /**
     * Show the financial overview page
     */
    public function financialOverview()
    {
        return view('financial.overview');
    }

    /**
     * Show the invoice creator page
     */
    public function invoiceCreator()
    {
        return view('financial.invoice-creator');
    }

    /**
     * Show the invoice viewer page
     */
    public function invoiceViewer()
    {
        return view('financial.invoice-viewer');
    }

    /**
     * Show the financial reports page
     */
    public function financialReports()
    {
        return view('financial.reports');
    }

    /**
     * Show the doctor earnings page
     */
    public function doctorEarnings()
    {
        return view('financial.doctor-earnings');
    }

    /**
     * Show the revenue tracking page
     */
    public function revenueTracking()
    {
        return view('financial.revenue-tracking');
    }

    /**
     * Show the admin login page
     */
    public function adminLogin()
    {
        return view('admin-login');
    }

    /**
     * Show the app/dashboard page (redirect to dashboard)
     */
    public function app()
    {
        return redirect('/dashboard');
    }

    /**
     * Show the home page (redirect to dashboard if authenticated)
     */
    public function home()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('welcome');
    }

    /**
     * Show the medgemma integration page
     */
    public function medgemmaIntegration()
    {
        return view('integrations.medgemma');
    }

    /**
     * Get CSRF token for frontend
     */
    public function csrfToken()
    {
        return response()->json(['csrf_token' => csrf_token()]);
    }

    /**
     * Health check endpoint
     */
    public function heartbeat()
    {
        return response()->json(['status' => 'ok', 'timestamp' => now()]);
    }
}
