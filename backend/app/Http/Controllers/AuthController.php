<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:3'
        ]);

        $credentials = $request->only('email', 'password');
        
        // Debug: Log the login attempt
        Log::info('Login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        // Set session timeout to 5 minutes (300 seconds)
        config(['session.lifetime' => 5]);
        
        // Attempt authentication
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();
            
            // Store last activity time for timeout checking
            $request->session()->put('last_activity', now());
            
            Log::info('Login successful', [
                'user_id' => Auth::id(),
                'email' => $credentials['email']
            ]);
            
            return redirect()->intended('/dashboard')->with('success', 'Welcome back!');
        }

        Log::warning('Login failed', [
            'email' => $credentials['email'],
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'You have been logged out successfully.');
    }
}
