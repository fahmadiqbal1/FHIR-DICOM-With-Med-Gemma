<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminBasicAuth
{
    public function handle(Request $request, Closure $next)
    {
        $user = env('ADMIN_BASIC_USER', 'admin');
        $pass = env('ADMIN_BASIC_PASSWORD', 'password');

        $u = $request->getUser();
        $p = $request->getPassword();

        if ($u === null) { $u = ''; }
        if ($p === null) { $p = ''; }

        if (!hash_equals((string)$user, (string)$u) || !hash_equals((string)$pass, (string)$p)) {
            $headers = ['WWW-Authenticate' => 'Basic realm="Admin Panel"'];
            return response('Unauthorized.', 401, $headers);
        }

        return $next($request);
    }
}
