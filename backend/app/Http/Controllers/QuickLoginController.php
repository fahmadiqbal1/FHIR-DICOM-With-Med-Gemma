<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class QuickLoginController extends Controller
{
    public function loginAsAdmin()
    {
        $admin = User::where('email', 'admin@medgemma.com')->first();
        if ($admin) {
            Auth::login($admin);
            return redirect()->route('financial.admin-dashboard');
        }
        return redirect('/login')->with('error', 'Admin user not found');
    }
    
    public function loginAsDoctor()
    {
        $doctor = User::where('email', 'doctor1@medgemma.com')->first();
        if ($doctor) {
            Auth::login($doctor);
            return redirect()->route('financial.doctor-dashboard');
        }
        return redirect('/login')->with('error', 'Doctor user not found');
    }
    
    public function showQuickLogin()
    {
        return view('quick-login');
    }
}
