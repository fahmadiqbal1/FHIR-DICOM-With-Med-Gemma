<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ApiRateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'api:'.$request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 100)) {
            return response()->json([
                'message' => 'Too many requests. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key)
            ], 429);
        }

        RateLimiter::hit($key, 60); // 60 seconds

        $response = $next($request);

        $response->headers->set('X-RateLimit-Limit', 100);
        $response->headers->set('X-RateLimit-Remaining', RateLimiter::remaining($key, 100));

        return $response;
    }
}