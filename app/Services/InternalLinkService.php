<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Solution;
use Illuminate\Support\Str;

class InternalLinkService
{
    public function catalog(?int $excludePost = null): array
    {
        $pages = [];
        foreach ([BlogPost::class => 'blog.show', Service::class => 'services', Solution::class => 'solutions', Portfolio::class => 'portfolio.show'] as $model => $route) {
            $query = $model::published()->orderByDesc('id');
            if ($model === BlogPost::class && $excludePost) {
                $query->whereKeyNot($excludePost);
            }
            foreach ($query->limit(150)->get(['id', 'title', 'slug']) as $item) {
                if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $item->slug)) {
                    continue;
                }
                $url = in_array($route, ['services', 'solutions'], true)
                    ? route($route, [], false) . '#' . $item->slug
                    : route($route, $item->slug, false);
                $pages[] = ['title' => $item->getBase('title'), 'url' => $url,
                    'kind' => $route, 'id' => $item->id];
            }
        }
        return $pages;
    }

    public function tokens(string $text): array
    {
        $stop = ['untuk', 'dengan', 'yang', 'dan', 'atau', 'dari', 'pada', 'dalam', 'the', 'and', 'for', 'bisnis', 'aldeftech', 'cara', 'manfaat'];
        return array_values(array_diff(array_unique(explode(' ', Str::slug($text, ' '))), $stop, ['']));
    }

    public function related(string $title, array $pages): array
    {
        $tokens = $this->tokens($title);
        foreach ($pages as &$page) {
            $page['score'] = count(array_intersect($tokens, $this->tokens($page['title'])));
        }
        unset($page);
        usort($pages, fn ($a, $b) => $b['score'] <=> $a['score']);
        return array_values(array_filter($pages, fn ($page) => $page['score'] >= 2));
    }

    public function improve(string $html, string $title, ?int $postId = null): string
    {
        $limit = min(4, max(0, (int) config('seo_growth.internal_link_limit', 3)));
        preg_match_all('/<a\b[^>]*href\s*=\s*["\']([^"\']+)["\']/i', $html, $matches);
        $used = array_map(fn ($url) => $this->destination($url), $matches[1]);
        $remaining = max(0, $limit - count($used));
        if (! $remaining) {
            return $html;
        }
        $pages = $this->related($title, $this->catalog($postId));
        // Tokenize without reserializing the editor's HTML. Only plain paragraph/list
        // text is eligible; never touch attributes, headings or existing links.
        $parts = preg_split('/(<[^>]*>)/s', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        $stack = [];
        foreach ($parts as &$part) {
            if (str_starts_with($part, '<')) {
                if (preg_match('/^<\/(\w+)/', $part)) {
                    array_pop($stack);
                } elseif (preg_match('/^<(\w+)/', $part, $tag) && ! in_array(strtolower($tag[1]), ['br', 'hr', 'img', 'input', 'source', 'wbr', 'meta', 'link'])) {
                    $stack[] = strtolower($tag[1]);
                }
                continue;
            }
            if (! $remaining || ! array_intersect($stack, ['p', 'li'])
                || array_intersect($stack, ['a', 'script', 'style', 'pre', 'code', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'button', 'figcaption'])) {
                continue;
            }
            foreach ($pages as $page) {
                if (in_array($this->destination($page['url']), $used, true) || mb_strlen($page['title']) < 5) {
                    continue;
                }
                $pattern = '/(?<![\pL\pN])' . preg_quote(e($page['title']), '/') . '(?![\pL\pN])/iu';
                $part = preg_replace_callback($pattern, fn ($m) => '<a href="' . e($page['url']) . '">' . $m[0] . '</a>', $part, 1, $count);
                if ($count) {
                    $used[] = $this->destination($page['url']);
                    $remaining--;
                    break;
                }
            }
        }
        unset($part);
        $html = implode('', $parts);
        // Full article titles rarely occur verbatim in prose. One clearly labeled
        // related reading link provides a useful connection without forced anchors.
        foreach ($pages as $page) {
            if ($remaining && $page['kind'] === 'blog.show' && ! in_array($this->destination($page['url']), $used, true)) {
                $html .= PHP_EOL . '<p>Bacaan terkait: <a href="' . e($page['url']) . '">' . e($page['title']) . '</a>.</p>';
                break;
            }
        }
        return $html;
    }

    private function destination(string $url): string
    {
        return (parse_url(html_entity_decode($url), PHP_URL_PATH) ?: '/') . '#' . (parse_url($url, PHP_URL_FRAGMENT) ?: '');
    }
}
