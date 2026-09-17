<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Consolidates the URL variants Google was crawling as separate documents.
 *
 * Every page was reachable, and declared itself canonical, on
 * https://www.aldeftech.com as well as on the bare host, and /faq/ answered 200
 * next to /faq. That produced 16 URLs in Search Console reported as
 * "Duplicate, Google chose different canonical than user".
 *
 * Two rules only, both 301 and both GET/HEAD:
 *   1. request scheme/host other than APP_URL's -> APP_URL's
 *   2. trailing slash on a non-root path        -> the same path without it
 *
 * Nothing is rewritten: no path changes destination and nothing redirects to
 * the homepage. A request that is already canonical passes straight through,
 * so the redirect cannot loop.
 */
class CanonicalRedirect
{
    /** Environments where a different host is expected to be legitimate. */
    private const SKIP_HOST_ENFORCEMENT = ['local', 'testing'];

    public function handle(Request $request, Closure $next): Response
    {
        if (! in_array($request->getMethod(), ['GET', 'HEAD'], true)) {
            return $next($request);
        }

        $base = rtrim((string) config('app.url'), '/');

        if ($base === '' || ! str_contains($base, '://')) {
            return $next($request);
        }

        // Split the raw request URI so the query string is preserved verbatim.
        $uri = $request->getRequestUri();
        $query = '';

        if (($pos = strpos($uri, '?')) !== false) {
            $query = substr($uri, $pos);
            $uri = substr($uri, 0, $pos);
        }

        $path = $uri === '/' ? '/' : rtrim($uri, '/');
        $hostIsCanonical = strcasecmp($request->getSchemeAndHttpHost(), $base) === 0
            || app()->environment(self::SKIP_HOST_ENFORCEMENT);

        $target = ($hostIsCanonical ? $request->getSchemeAndHttpHost() : $base) . $path;

        if ($target . $query === $request->getUri()) {
            return $next($request);
        }

        return redirect()->to($target . $query, 301);
    }
}
