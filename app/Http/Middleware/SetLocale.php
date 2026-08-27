<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Pick the request locale.
     *
     * Order of preference: an explicit choice stored in the session, then the
     * visitor's Accept-Language header, then the configured default. The header
     * is only consulted on a first visit so a deliberate switch always wins.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('locales.available', []));
        $default = config('locales.default', 'id');

        $locale = $request->session()->get('locale');

        if (! in_array($locale, $available, true)) {
            $locale = $request->getPreferredLanguage($available) ?: $default;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
