<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
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

    /** Hosts that can never be the public address of the site. */
    private const LOOPBACK_HOSTS = ['localhost', '127.0.0.1', '::1', '[::1]'];

    public function handle(Request $request, Closure $next): Response
    {
        $this->replaceLoopbackAppUrl($request);

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

        if ($target . $query === $request->getSchemeAndHttpHost() . $request->getRequestUri()) {
            return $next($request);
        }

        return redirect()->to($target . $query, 301);
    }

    /**
     * APP_URL feeds every SEO signal: the canonical tag, hreflang, sitemap,
     * robots.txt and the JSON-LD @id values. A server set up from .env.example
     * kept APP_URL=http://localhost:8000, and production then told Google that
     * every page lived on localhost. A loopback APP_URL is never the public
     * address, so when a request arrives on a real host, that host is used for
     * this request and a warning is logged once a day until the .env is fixed.
     */
    private function replaceLoopbackAppUrl(Request $request): void
    {
        $configured = strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST));

        if ($configured !== '' && ! in_array($configured, self::LOOPBACK_HOSTS, true)) {
            return;
        }

        if (in_array(strtolower($request->getHost()), self::LOOPBACK_HOSTS, true)) {
            return;
        }

        config(['app.url' => $request->getSchemeAndHttpHost()]);
        URL::forceRootUrl($request->getSchemeAndHttpHost());

        if (Cache::add('app_url_loopback_warned', true, now()->addDay())) {
            Log::warning('APP_URL points at a loopback host; using the request host for canonical URLs. Set APP_URL in .env.', [
                'app_url' => $configured,
                'request_host' => $request->getHost(),
            ]);
        }
    }
}
