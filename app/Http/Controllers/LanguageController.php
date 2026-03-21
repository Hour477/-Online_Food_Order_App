<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(string $locale): RedirectResponse
    {
        $supportedLocales = config('app.supported_locales', ['en', 'km']);
        
        Log::info('LanguageController::switch - Request locale: ' . $locale);
        Log::info('LanguageController::switch - Current URL: ' . url()->current());
        Log::info('LanguageController::switch - Previous URL: ' . url()->previous());
        
        if (in_array($locale, $supportedLocales)) {
            // Store in session
            Session::put('locale', $locale);
            
            Log::info('LanguageController::switch - Session saved', [
                'locale' => $locale,
                'session_id' => Session::getId()
            ]);
            
            // Set a cookie for persistence across requests
            $cookie = cookie('locale', $locale, 525600); // 1 year
            Cookie::queue($cookie);
        } else {
            Log::warning('LanguageController::switch - Unsupported locale: ' . $locale);
        }
        
        // Redirect back or to home
        $previousUrl = url()->previous() ?: route('customerOrder.menu.index');
        
        Log::info('LanguageController::switch - Redirecting to: ' . $previousUrl);
        
        return redirect($previousUrl);
    }
}
