<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\RoleHelper;
use Symfony\Component\HttpFoundation\Response;

class RadiologistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = Auth::user();
        
        // Check if user has radiologist role using helper
        if (!RoleHelper::isRadiologist($user)) {
            return response()->json(['error' => 'Access denied. Radiologist role required.'], 403);
        }

        return $next($request);
    }
}
