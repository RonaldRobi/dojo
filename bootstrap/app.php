<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
            $middleware->alias([
                'dojo' => \App\Http\Middleware\EnsureDojoAccess::class,
                'ensure.dojo.access' => \App\Http\Middleware\EnsureDojoAccess::class,
                'permission' => \App\Http\Middleware\PermissionMiddleware::class,
                'role' => \App\Http\Middleware\RoleMiddleware::class,
                'ensure.account.active' => \App\Http\Middleware\EnsureAccountActive::class,
                'check.unpaid.registration' => \App\Http\Middleware\CheckUnpaidRegistration::class,
            ]);
            
            // Exclude Bayar.cash payment gateway routes from CSRF verification
            $middleware->validateCsrfTokens(except: [
                'parent/payment/return/*',
                'parent/payment/callback',
                'parent/payment/webhook',
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
