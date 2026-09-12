<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class SeoPublicUrlVerifier
{
    public function verify(string $url): ?string
    {
        // Follow only a few explicitly checked redirects. Pin DNS for each hop to
        // prevent a search result from reaching private services via DNS rebinding.
        for ($hop = 0; $hop < 4; $hop++) {
            if (! filter_var($url, FILTER_VALIDATE_URL) || strlen($url) > 1000) {
                return null;
            }
            $parts = parse_url($url);
            $host = strtolower($parts['host'] ?? '');
            if (($parts['scheme'] ?? '') !== 'https' || isset($parts['user']) || isset($parts['pass'])
                || (isset($parts['port']) && $parts['port'] !== 443)
                || ! preg_match('/^[a-z0-9.-]+\.[a-z]{2,}$/', $host)) {
                return null;
            }
            $ips = gethostbynamel($host) ?: [];
            if (! $ips) {
                return null;
            }
            foreach ($ips as $ip) {
                if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return null;
                }
            }
            try {
                $response = Http::connectTimeout(3)->timeout(6)->withoutRedirecting()
                    ->withOptions(['proxy' => '', 'curl' => [CURLOPT_RESOLVE => [$host . ':443:' . $ips[0]]]])
                    ->head($url);
            } catch (Throwable $e) {
                return null;
            }
            if ($response->successful()) {
                return $url;
            }
            if (! in_array($response->status(), [301, 302, 303, 307, 308], true)) {
                return null;
            }
            $next = $response->header('Location');
            if (str_starts_with($next, '/') && ! str_starts_with($next, '//')) {
                $next = 'https://' . $host . $next;
            }
            $url = $next;
        }
        return null;
    }
}
