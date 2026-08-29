<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Take the language from the URL, not the session.
     *
     * A session-held locale gave English no address of its own, so Googlebot —
     * which carries no session — only ever saw Indonesian and the whole English
     * translation stayed unindexed. The first path segment now decides: /en/...
     * is English, everything else is Indonesian.
     *
     * Accept-Language is still deliberately ignored. This is an Indonesian
     * company site, and sniffing the header served English to anyone whose
     * browser happened to be set to it.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('locales.available', []));
        $default = config('locales.default', 'id');

        $segment = $request->segment(1);

        $locale = (in_array($segment, $available, true) && $segment !== $default)
            ? $segment
            : $default;

        App::setLocale($locale);

        return $next($request);
    }
}
