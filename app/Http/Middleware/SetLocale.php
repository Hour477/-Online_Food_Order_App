<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for locale in this order: session > cookie > config default
        $locale = null;
        
        // First check session
        if (Session::has('locale')) {
            $locale = Session::get('locale');
            Log::info('SetLocale - Using session locale', ['locale' => $locale]);
        } 
        // Then check cookie
        elseif ($request->hasCookie('locale')) {
            $locale = $request->cookie('locale');
            Log::info('SetLocale - Using cookie locale', ['locale' => $locale]);
            // Sync session with cookie for subsequent requests
            Session::put('locale', $locale);
        } 
        // Fallback to config default
        else {
            $locale = config('app.locale', 'en');
            Log::info('SetLocale - Using default locale', ['locale' => $locale]);
        }

        // Validate locale is supported
        $supportedLocales = config('app.supported_locales', ['en', 'km']);
        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale', 'en');
            Log::warning('SetLocale - Invalid locale, using fallback', ['locale' => $locale]);
        }
        
        // Set application locale
        App::setLocale($locale);
        Log::debug('SetLocale - Locale set', ['locale' => $locale]);

        return $next($request);
    }
}
