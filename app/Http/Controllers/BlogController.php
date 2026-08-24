<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()
            ->with('category', 'author')
            ->latest('published_at')
            ->paginate(9);

        $categories = BlogCategory::withCount('posts')->orderBy('sort_order')->get();

        return view('pages.blog', compact('posts', 'categories'));
    }

    public function show(BlogPost $post)
    {
        if (!$post->isPublished()) {
            abort(404);
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
}
