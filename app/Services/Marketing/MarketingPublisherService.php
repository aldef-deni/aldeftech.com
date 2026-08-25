<?php

namespace App\Services\Marketing;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use App\Models\MarketingContent;
use Illuminate\Support\Str;

class MarketingPublisherService
{
    public function publishToBlog(MarketingContent $content, ?int $authorId): BlogPost
    {
        $content->load(['campaign', 'idea.contentPillar', 'blogPost']);

        $category = BlogCategory::firstOrCreate(
            ['slug' => 'digital-transformation'],
            [
                'name' => 'Digital Transformation',
                'description' => 'Insights Aldef Tech tentang sistem, aplikasi, AI, dan digitalisasi bisnis.',
                'sort_order' => 10,
            ]
        );

        $payload = [
            'title' => $content->title,
            'excerpt' => $content->excerpt,
            'content' => $this->normalizeHtml($content->content ?? ''),
            'category_id' => $category->id,
            'author_id' => $authorId,
            'status' => 'published',
            'published_at' => now(),
            'meta_title' => $content->seo_title,
            'meta_description' => $content->seo_description,
            'canonical_url' => null,
        ];

        if ($content->blogPost) {
            $content->blogPost->update($payload);
            $post = $content->blogPost;
        } else {
            $post = BlogPost::create(array_merge($payload, [
                'slug' => $this->uniqueSlug($content->title),
            ]));
        }

        $post->tags()->sync($this->tagIds($content));

        $content->update([
            'status' => 'published',
            'approved_at' => $content->approved_at ?? now(),
            'published_at' => now(),
            'published_blog_post_id' => $post->id,
        ]);

        return $post;
    }

    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'marketing-content';
        $slug = $base;
        $counter = 2;

        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function tagIds(MarketingContent $content): array
    {
        $keywords = collect(explode(',', (string) $content->seo_keywords))
            ->map(fn ($keyword) => trim($keyword))
            ->filter()
            ->take(8);

        $pillar = $content->idea?->contentPillar?->name;

        if ($pillar) {
            $keywords->push($pillar);
        }

        return $keywords
            ->unique(fn ($keyword) => Str::lower($keyword))
            ->map(function (string $keyword) {
                return BlogTag::firstOrCreate(
                    ['slug' => Str::slug($keyword)],
                    ['name' => Str::headline($keyword)]
                )->id;
            })
            ->values()
            ->all();
    }

    protected function normalizeHtml(string $content): string
    {
        if (
            str_contains($content, '<p') ||
            str_contains($content, '<h2') ||
            str_contains($content, '<ul')
        ) {
            return $content;
        }

        return collect(preg_split("/\R{2,}/", trim($content)))
            ->filter()
            ->map(fn ($paragraph) => '<p>' . nl2br(e($paragraph)) . '</p>')
            ->implode("\n");
    }
}
