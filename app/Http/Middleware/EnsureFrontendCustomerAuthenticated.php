<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendCustomerAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('frontend_customer_id')) {
            return redirect()->route('frontend.login')
                ->with('error', 'Please login first.');
        }

        return $next($request);
    }
}

