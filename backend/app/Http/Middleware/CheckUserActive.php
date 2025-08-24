<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip check for quick login routes and login routes
        if ($request->is('quick-login/*') || $request->is('login') || $request->is('auth/*')) {
            return $next($request);
        }
        
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is active based on role
            if ($this->isUserInactive($user)) {
                Auth::logout();
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Your account has been deactivated. Please contact the administrator.'], 403);
                }
                
                return redirect('/login')->with('error', 'Your account has been deactivated. Please contact the administrator.');
            }
        }
        
        return $next($request);
    }
    
    /**
     * Check if user should be considered inactive
     */
    private function isUserInactive($user): bool
    {
        // For doctors, radiologists, and other medical staff, check is_active_doctor
        if (in_array($user->role, ['doctor', 'Doctor', 'radiologist', 'Radiologist', 'lab_tech', 'Lab_Tech', 'pharmacist', 'Pharmacist'])) {
            return $user->is_active_doctor != 1;
        }
        
        // For admin users, check if they have admin role but no is_active_doctor status
        if (in_array($user->role, ['admin', 'Admin', 'owner', 'Owner'])) {
            // Admins and owners are considered active by default unless explicitly set to inactive
            return false; // You can add more specific logic here if needed
        }
        
        // For users without role, check is_active_doctor
        if (empty($user->role)) {
            return $user->is_active_doctor != 1;
        }
        
        return false;
    }
}
