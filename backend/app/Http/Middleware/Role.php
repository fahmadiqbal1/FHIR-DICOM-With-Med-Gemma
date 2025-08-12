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

            $userRoles = $request->user()->roles->pluck('name')->toArray();
            
            // Check if user has any of the required roles
            $hasRole = false;
            foreach ($roles as $role) {
                if (in_array($role, $userRoles)) {
                    $hasRole = true;
                    break;
                }
            }
            
            if (!$hasRole) {
                return response()->json(['error' => 'Unauthorized.'], 403);
            }
        }

        return $next($request);
    }
}