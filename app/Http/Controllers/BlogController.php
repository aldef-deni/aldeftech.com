<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogPostSlugRedirect;

class BlogController extends Controller
{
    /**
     * How many articles to compare a legacy slug against when looking for one
     * whose stored slug still carries a UUID tail. Slugs are unique, so a hit
     * is a hit; this only bounds the scan when there is none.
     */
    private const LEGACY_SCAN_LIMIT = 200;

    public function index()
    {
        $posts = BlogPost::published()
            ->with('category', 'author')
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::withCount('posts')->orderBy('sort_order')->get();

        return view('pages.blog', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        /*
         * The slug is resolved here rather than by route-model binding, because
         * an address that no longer matches must be forwarded, not 404'd.
         * Binding answers 404 the moment the column misses, which is exactly
         * what turns a renamed article — or an old UUID-suffixed one — into a
         * "Not found" report in Search Console.
         */
        $post = $this->resolvePost($slug);

        abort_unless($post, 404);

        if (!$post->isPublished()) {
            abort(404);
        }

        // A permanent move, never a silent 200: the crawler has to be told that
        // this address is the old one so it drops it and keeps the new.
        if ($post->slug !== $slug) {
            return redirect()->to(lroute('blog.show', $post->slug), 301);
        }

        $post->load(['category', 'author', 'tags']);

        $relatedPosts = BlogPost::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with('category')
            ->limit(3)
            ->get();

        return view('pages.blog-show', ['post' => $post, 'relatedPosts' => $relatedPosts]);
    }

    /**
     * An article by its current slug, an old slug it was published under, or a
     * legacy slug that still has the UUID tail appended.
     */
    private function resolvePost(string $slug): ?BlogPost
    {
        $post = BlogPost::where('slug', $slug)->first();

        if ($post) {
            return $post;
        }

        if ($recorded = BlogPostSlugRedirect::findCurrentPost($slug)) {
            return $recorded;
        }

        // The UUID tail was never part of the address anyone wanted. Strip it
        // and look again, then check the reverse: the address is already clean
        // but the stored slug still carries the tail.
        $clean = BlogPost::cleanSlug($slug);

        if ($clean !== $slug) {
            return BlogPost::where('slug', $clean)->first();
        }

        if ($clean === '') {
            return null;
        }

        return BlogPost::where('slug', 'like', $clean . '-%')
            ->limit(self::LEGACY_SCAN_LIMIT)
            ->get()
            ->first(fn (BlogPost $candidate) => BlogPost::cleanSlug($candidate->slug) === $clean);
    }
}
