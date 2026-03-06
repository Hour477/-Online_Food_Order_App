<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
   public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $userRole = strtolower($user->role ?? '');

        // If user role is in allowed roles
        if($userRole === "admin"){
            return $next($request);
        }else if($userRole === "waiter"){
            return $next($request);
        }
        abort(403, 'You are not allowed to access this page.');

        return $next($request);
    }

}
