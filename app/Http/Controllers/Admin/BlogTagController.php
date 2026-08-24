<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('posts')->orderBy('name')->get();
        return view('admin.tags.index', ['tags' => $tags]);
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        BlogTag::create($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(BlogTag $tag)
    {
        return view('admin.tags.edit', ['tag' => $tag]);
    }

    public function update(Request $request, BlogTag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $tag->update($validated);

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(BlogTag $tag)
    {
        $tag->delete();

        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
