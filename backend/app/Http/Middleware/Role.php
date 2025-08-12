<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // In production, we would check the user's role
        // For now, we'll allow all requests in development/testing
        if (app()->environment('production')) {
            if (!$request->user()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            // Check if user has roles relationship
            $hasRole = false;
            
            if (method_exists($request->user(), 'hasRole')) {
                // Use Spatie's hasRole method if available
                foreach ($roles as $role) {
                    if ($request->user()->hasRole($role)) {
                        $hasRole = true;
                        break;
                    }
                }
            } else if (method_exists($request->user(), 'roles') && $request->user()->roles) {
                // Fallback to checking roles relationship
                $userRoles = $request->user()->roles->pluck('name')->toArray();
                foreach ($roles as $role) {
                    if (in_array($role, $userRoles)) {
                        $hasRole = true;
                        break;
                    }
                }
            } else {
                // If no role system is available, allow access in non-production
                $hasRole = !app()->environment('production');
            }
            
            if (!$hasRole) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
        }

        return $next($request);
    }
}