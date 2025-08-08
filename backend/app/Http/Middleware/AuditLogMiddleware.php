<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            // Avoid logging sensitive payloads; only meta data
            $details = [
                'route' => optional($request->route())->getName(),
                'method' => $request->getMethod(),
                'path' => $request->path(),
                'params' => $request->route() ? $request->route()->parameters() : [],
                'status' => $response->getStatusCode(),
            ];

            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $details['method'].' '.$details['path'],
                'subject_type' => null,
                'subject_id' => null,
                'ip' => $request->ip(),
                'user_agent' => (string) $request->header('User-Agent'),
                'details' => $details,
            ]);
        } catch (\Throwable $e) {
            // Never break request flow due to audit logging
        }

        return $response;
    }
}
