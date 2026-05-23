<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     * Reads the locale from the session and sets the application locale.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'en'));

        // Validate that the locale is supported
        $supportedLocales = ['en', 'id'];
        if (!in_array($locale, $supportedLocales)) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
