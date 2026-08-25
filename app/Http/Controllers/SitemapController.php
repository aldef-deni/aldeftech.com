<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $url = rtrim(config('app.url', 'https://aldeftech.com'), '/');

        $pages = [
            '/' => ['changefreq' => 'weekly', 'priority' => '1.0'],
            '/about' => ['changefreq' => 'monthly', 'priority' => '0.8'],
            '/services' => ['changefreq' => 'monthly', 'priority' => '0.9'],
            '/solutions' => ['changefreq' => 'monthly', 'priority' => '0.9'],
            '/portfolio' => ['changefreq' => 'weekly', 'priority' => '0.8'],
            '/blog' => ['changefreq' => 'weekly', 'priority' => '0.8'],
            '/contact' => ['changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        /*
         * Static pages
         */
        foreach ($pages as $path => $settings) {
            $xml .= '<url>';
            $xml .= '<loc>' . e($url . $path) . '</loc>';
            $xml .= '<changefreq>' . $settings['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $settings['priority'] . '</priority>';
            $xml .= '</url>';
        }

        /*
         * Published portfolio pages
         */
        Portfolio::published()
            ->get()
            ->each(function ($portfolio) use (&$xml, $url) {
                $xml .= '<url>';
                $xml .= '<loc>' . e($url . '/portfolio/' . $portfolio->slug) . '</loc>';

                if ($portfolio->updated_at) {
                    $xml .= '<lastmod>' . $portfolio->updated_at->format('Y-m-d') . '</lastmod>';
                }

                $xml .= '<changefreq>monthly</changefreq>';
                $xml .= '<priority>0.7</priority>';
                $xml .= '</url>';
            });

        /*
         * Published blog pages
         */
        BlogPost::published()
            ->get()
            ->each(function ($post) use (&$xml, $url) {
                $xml .= '<url>';
                $xml .= '<loc>' . e($url . '/blog/' . $post->slug) . '</loc>';

                if ($post->updated_at) {
                    $xml .= '<lastmod>' . $post->updated_at->format('Y-m-d') . '</lastmod>';
                }

                $xml .= '<changefreq>monthly</changefreq>';
                $xml .= '<priority>0.6</priority>';
                $xml .= '</url>';
            });

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $url = rtrim(config('app.url', 'https://aldeftech.com'), '/');

        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /storage/\n\n";
        $robots .= "Sitemap: {$url}/sitemap.xml\n";

        return response($robots, 200)
            ->header('Content-Type: text/plain; charset=UTF-8');
    }
}
