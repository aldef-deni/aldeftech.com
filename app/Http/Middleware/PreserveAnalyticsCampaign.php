<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class PreserveAnalyticsCampaign
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || $request->is('admin', 'admin/*')
            || ! $response instanceof RedirectResponse) {
            return $response;
        }

        $campaign = array_filter($request->only([
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
            'gclid', 'gbraid', 'wbraid', 'fbclid',
        ]), fn ($value) => is_string($value) && $value !== '');

        if ($campaign === []) {
            return $response;
        }

        $target = $response->getTargetUrl();
        $parts = parse_url($target);
        if ($parts === false
            || (isset($parts['scheme']) && ! in_array($parts['scheme'], ['http', 'https'], true))
            || (isset($parts['host']) && strcasecmp($parts['host'], $request->getHost()) !== 0)
            || preg_match('~^/(?:en/)?admin(?:/|$)~', $parts['path'] ?? '')) {
            return $response;
        }

        parse_str($parts['query'] ?? '', $existing);
        $missing = array_diff_key($campaign, $existing);
        if ($missing !== []) {
            ksort($missing, SORT_STRING);
            [$url, $fragment] = array_pad(explode('#', $target, 2), 2, null);
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($missing, '', '&', PHP_QUERY_RFC3986);
            $response->setTargetUrl($url . ($fragment !== null ? '#' . $fragment : ''));
        }

        return $response;
    }
}
