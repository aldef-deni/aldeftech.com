<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\CeoProfile;
use App\Models\Faq;
use App\Models\PageSeo;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Solution;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $url = rtrim(config('app.url', 'https://aldeftech.com'), '/');

        /*
         * Static pages.
         *
         * lastmod is derived from the content each page actually renders, not
         * invented — Google ignores a lastmod it finds untrustworthy, and a
         * date that never matches a real change is worse than none at all.
         */
        $pages = [
            '/' => [
                'priority' => '1.0',
                'lastmod' => $this->latestOf([Service::class, Solution::class, Portfolio::class, Testimonial::class, BlogPost::class]),
            ],
            '/services' => ['priority' => '0.9', 'lastmod' => $this->latestOf([Service::class])],
            '/solutions' => ['priority' => '0.9', 'lastmod' => $this->latestOf([Solution::class])],
            '/portfolio' => ['priority' => '0.8', 'lastmod' => $this->latestOf([Portfolio::class])],
            '/blog' => ['priority' => '0.8', 'lastmod' => $this->latestOf([BlogPost::class])],
            '/faq' => ['priority' => '0.7', 'lastmod' => $this->latestOf([Faq::class])],
            '/about' => ['priority' => '0.8', 'lastmod' => $this->latestOf([CeoProfile::class, SiteSetting::class])],
            '/contact' => ['priority' => '0.7', 'lastmod' => $this->latestOf([SiteSetting::class])],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
              . ' xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // A page hidden from search must not be advertised in the sitemap either;
        // the two saying different things is a contradiction Google reports.
        $hidden = PageSeo::where('noindex', true)->pluck('route_name')->unique();

        foreach ($pages as $path => $meta) {
            $route = $path === '/' ? 'home' : ltrim($path, '/');

            if ($hidden->contains($route)) {
                continue;
            }

            $xml .= $this->urlNode($path, $meta['lastmod'], $meta['priority']);
        }

        Portfolio::published()->get()->each(function ($portfolio) use (&$xml, $url) {
            $xml .= $this->urlNode('/portfolio/' . $portfolio->slug, $portfolio->updated_at, '0.7');
        });

        BlogPost::published()->get()->each(function ($post) use (&$xml, $url) {
            $xml .= $this->urlNode('/blog/' . $post->slug, $post->updated_at, '0.6');
        });

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $url = rtrim(config('app.url', 'https://aldeftech.com'), '/');

        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            'Disallow: /admin/',
            'Disallow: /storage/',
            // Switches the session locale and bounces straight back, so every
            // one of these is a duplicate of a page already in the sitemap.
            'Disallow: /lang/',
            'Disallow: /clear-cache',
            '',
            "Sitemap: {$url}/sitemap.xml",
            '',
        ];

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * One <url> per language, each listing every language as an alternate.
     *
     * Google wants the alternates repeated inside every entry — a page that
     * only points outward, without the return link, has its hreflang ignored.
     *
     * @param  string  $path  path after the locale prefix, e.g. '/services'
     */
    private function urlNode(string $path, ?Carbon $lastmod, string $priority): string
    {
        $base = rtrim(config('app.url', 'https://aldeftech.com'), '/');
        $default = config('locales.default', 'id');
        $locales = config('locales.available', []);

        $hrefs = [];
        foreach ($locales as $code => $meta) {
            $prefix = $code === $default ? '' : '/' . $code;
            $hrefs[$code] = $base . $prefix . ($path === '/' ? '' : $path);
        }
        // '/' collapses to the bare host, which some validators reject.
        $hrefs = array_map(fn ($h) => $h === $base ? $base . '/' : $h, $hrefs);

        $alternates = '';
        foreach ($hrefs as $code => $href) {
            $alternates .= '<xhtml:link rel="alternate" hreflang="'
                . ($locales[$code]['html'] ?? $code) . '" href="' . e($href) . '"/>';
        }
        $alternates .= '<xhtml:link rel="alternate" hreflang="x-default" href="'
            . e($hrefs[$default]) . '"/>';

        $out = '';
        foreach ($hrefs as $href) {
            $out .= '<url><loc>' . e($href) . '</loc>';
            if ($lastmod) {
                $out .= '<lastmod>' . $lastmod->toAtomString() . '</lastmod>';
            }
            $out .= '<priority>' . $priority . '</priority>' . $alternates . '</url>';
        }

        return $out;
    }

    /**
     * Newest updated_at across the given models, or null when nothing exists.
     *
     * @param  array<class-string<Model>>  $models
     */
    private function latestOf(array $models): ?Carbon
    {
        $latest = null;

        foreach ($models as $model) {
            $stamp = $model::query()->max('updated_at');

            if (! $stamp) {
                continue;
            }

            $stamp = Carbon::parse($stamp);

            if (! $latest || $stamp->greaterThan($latest)) {
                $latest = $stamp;
            }
        }

        return $latest;
    }
}
