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
        return redirect()->route('login');
        }
        $user = Auth::user();
        // Safe null check + get slug
        $userRoleSlug = $user->role?->slug ?? null;
        if (!$userRoleSlug || !in_array($userRoleSlug, $roles)) {
            abort(403, 'You do not have permission to access this area.');
        }
        return $next($request);
        

        
    }
}
