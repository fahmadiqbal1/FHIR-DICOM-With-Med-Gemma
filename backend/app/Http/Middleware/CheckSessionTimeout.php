<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity');
            $timeout = 5 * 60; // 5 minutes in seconds
            
            if ($lastActivity && (now()->timestamp - $lastActivity->timestamp) > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired'], 401);
                }
                
                return redirect('/login')->with('error', 'Your session has expired due to inactivity.');
            }
            
            // Update last activity
            $request->session()->put('last_activity', now());
        }
        
        return $next($request);
    }
}
