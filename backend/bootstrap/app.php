<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuditLogMiddleware;
use App\Http\Middleware\Role;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.basic' => \App\Http\Middleware\AdminBasicAuth::class,
            'role' => \App\Http\Middleware\Role::class,
            'session.timeout' => \App\Http\Middleware\CheckSessionTimeout::class,
        ]);
        $middleware->append(AuditLogMiddleware::class);
        $middleware->web(append: [
            \App\Http\Middleware\CheckSessionTimeout::class,
        ]);
        // Disable CSRF for API routes
        $middleware->validateCsrfTokens(except: [
            'api/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
