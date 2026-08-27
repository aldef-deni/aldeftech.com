<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category', 'author')->latest()->paginate(20);
        return view('admin.blog.index', ['posts' => $posts]);
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();
        return view('admin.blog.create', ['categories' => $categories, 'tags' => $tags]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        $validated['published_at'] = $validated['status'] === 'published'
            ? ($validated['published_at'] ?? now())
            : null;

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $post = BlogPost::create($validated);
        $post->tags()->sync($tags);

        ActivityLog::log('blog.created', "Created blog post \"{$post->title}\"", $post);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully.');
    }

    public function edit(BlogPost $blog)
    {
        $blog->load('tags');
        $categories = BlogCategory::orderBy('name')->get();
        $tags = BlogTag::orderBy('name')->get();

        return view('admin.blog.edit', ['post' => $blog, 'categories' => $categories, 'tags' => $tags]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:blog_categories,id',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'canonical_url' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['published_at'] = $validated['status'] === 'published' && !$blog->published_at
            ? now()
            : $validated['published_at'];

        $tags = $validated['tags'] ?? [];
        unset($validated['tags']);

        $blog->update($validated);
        $blog->tags()->sync($tags);

        ActivityLog::log('blog.updated', "Updated blog post \"{$blog->title}\"", $blog);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blog)
    {
        $title = $blog->title;
        $blog->delete();
        ActivityLog::log('blog.deleted', "Deleted blog post \"{$title}\"");

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully.');
    }

    public function togglePublish(BlogPost $blog)
    {
        $newStatus = $blog->status === 'published' ? 'draft' : 'published';
        $blog->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($blog->published_at ?? now()) : null,
        ]);

        return back()->with('success', 'Publish status updated.');
    }
}
