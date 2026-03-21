<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureFrontendCustomerAuthenticated;
use App\Http\Middleware\RoleMiddleware; // Import your middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Register middleware aliases
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'frontend.customer.auth' => EnsureFrontendCustomerAuthenticated::class,
        ]);

        // Register SetLocale middleware in the web group
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
