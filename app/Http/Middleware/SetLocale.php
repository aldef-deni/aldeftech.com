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
     * Indonesian is the default for every visitor. Accept-Language is
     * deliberately ignored: this is an Indonesian company site, and sniffing
     * the header served English to anyone whose browser happened to be set to
     * it. Only an explicit choice from the switcher changes the language.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('locales.available', []));
        $default = config('locales.default', 'id');

        $locale = $request->session()->get('locale');

        if (! in_array($locale, $available, true)) {
            $locale = $default;
        }

        App::setLocale($locale);

        return $next($request);
    }
}
