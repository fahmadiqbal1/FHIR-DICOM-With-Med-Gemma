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
            return redirect('/dashboard'); // Fixed: redirect to main dashboard
        }
        return redirect('/login')->with('error', 'Admin user not found');
    }
    
    public function loginAsDoctor()
    {
        $doctor = User::where('email', 'doctor1@medgemma.com')->first();
        if ($doctor) {
            Auth::login($doctor);
            return redirect('/patients'); // Fixed: redirect to patients page for doctors
        }
        return redirect('/login')->with('error', 'Doctor user not found');
    }
    
    public function loginAsRadiologist()
    {
        $radiologist = User::where('email', 'radiologist@medgemma.com')->first();
        if ($radiologist) {
            Auth::login($radiologist);
            return redirect()->route('radiologist.dashboard');
        }
        return redirect('/login')->with('error', 'Radiologist user not found');
    }
    
    public function loginAsLabTech()
    {
        $labTech = User::where('email', 'labtech@medgemma.com')->first();
        if ($labTech) {
            Auth::login($labTech);
            return redirect()->route('lab-tech.dashboard');
        }
        return redirect('/login')->with('error', 'Lab Technician user not found');
    }
    
    public function loginAsOwner()
    {
        $owner = User::where('email', 'owner@medgemma.com')->first();
        if ($owner) {
            Auth::login($owner);
            return redirect('/dashboard'); // Owner sees owner-dashboard through dashboard route
        }
        return redirect('/login')->with('error', 'Owner user not found');
    }
    
    public function showQuickLogin()
    {
        return view('quick-login');
    }
}
