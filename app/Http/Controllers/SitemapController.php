<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $url = config('app.url', 'https://aldeftech.com');

        $pages = [
            '/',
            '/about',
            '/services',
            '/solutions',
            '/portfolio',
            '/blog',
            '/contact',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($pages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . $page . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>' . ($page === '/' ? '1.0' : '0.8') . '</priority>';
            $xml .= '</url>';
        }

        foreach (Service::published()->get() as $item) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . '/services</loc>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        foreach (Solution::published()->get() as $item) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . '/solutions</loc>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        foreach (Portfolio::published()->get() as $portfolio) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . '/portfolio/' . $portfolio->slug . '</loc>';
            $xml .= '<lastmod>' . $portfolio->updated_at->format('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.7</priority>';
            $xml .= '</url>';
        }

        foreach (BlogPost::published()->get() as $post) {
            $xml .= '<url>';
            $xml .= '<loc>' . $url . '/blog/' . $post->slug . '</loc>';
            $xml .= '<lastmod>' . $post->updated_at->format('Y-m-d') . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.6</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function robots(): Response
    {
        $url = config('app.url', 'https://aldeftech.com');

        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /storage/\n\n";
        $robots .= "Sitemap: {$url}/sitemap.xml\n";

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }
}
