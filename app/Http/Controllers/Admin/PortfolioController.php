<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\PortfolioImage;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PortfolioController extends Controller
{
    use SavesTranslations;

    public function index()
    {
        $portfolios = Portfolio::with('category')->ordered()->get();
        return view('admin.portfolio.index', ['portfolios' => $portfolios]);
    }

    public function create()
    {
        $categories = PortfolioCategory::orderBy('sort_order')->get();
        return view('admin.portfolio.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:portfolio_categories,id',
            'client' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:20',
            'featured_image' => 'nullable|string|max:500',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string',
            'project_url' => 'nullable|url|max:500',
            'challenge' => 'nullable|string',
            'approach' => 'nullable|string',
            'solution' => 'nullable|string',
            'results' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'string|max:500',
            'captions' => 'nullable|array',
            'captions.*' => 'string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['technologies'] = $request->input('technologies', []);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $request->input('published_at') ?? now();

        $portfolio = Portfolio::create($validated);
        $this->saveTranslations($request, $portfolio);

        // Handle gallery images
        if ($images = $request->input('images')) {
            foreach ($images as $index => $image) {
                PortfolioImage::create([
                    'portfolio_id' => $portfolio->id,
                    'image' => $image,
                    'caption' => $request->input("captions.{$index}", ''),
                    'sort_order' => $index,
                ]);
            }
        }

        ActivityLog::log('portfolio.created', "Created portfolio \"{$portfolio->title}\"", $portfolio);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio created successfully.');
    }

    public function edit(Portfolio $portfolio)
    {
        $portfolio->load('images');
        $categories = PortfolioCategory::orderBy('sort_order')->get();

        return view('admin.portfolio.edit', ['portfolio' => $portfolio, 'categories' => $categories]);
    }

    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:portfolio_categories,id',
            'client' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:20',
            'featured_image' => 'nullable|string|max:500',
            'technologies' => 'nullable|array',
            'technologies.*' => 'string',
            'project_url' => 'nullable|url|max:500',
            'challenge' => 'nullable|string',
            'approach' => 'nullable|string',
            'solution' => 'nullable|string',
            'results' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['technologies'] = $request->input('technologies', []);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_published'] = $request->boolean('is_published');

        $portfolio->update($validated);
        $this->saveTranslations($request, $portfolio);

        // Handle gallery images
        if ($images = $request->input('images')) {
            $portfolio->images()->delete();
            foreach ($images as $index => $image) {
                PortfolioImage::create([
                    'portfolio_id' => $portfolio->id,
                    'image' => $image,
                    'caption' => $request->input("captions.{$index}", ''),
                    'sort_order' => $index,
                ]);
            }
        }

        ActivityLog::log('portfolio.updated', "Updated portfolio \"{$portfolio->title}\"", $portfolio);

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio updated successfully.');
    }

    public function destroy(Portfolio $portfolio)
    {
        $title = $portfolio->title;
        $portfolio->delete();
        ActivityLog::log('portfolio.deleted', "Deleted portfolio \"{$title}\"");

        return redirect()->route('admin.portfolio.index')->with('success', 'Portfolio deleted successfully.');
    }

    public function toggleFeatured(Portfolio $portfolio)
    {
        $portfolio->update(['is_featured' => !$portfolio->is_featured]);

        return back()->with('success', 'Featured status updated.');
    }

    public function togglePublished(Portfolio $portfolio)
    {
        $portfolio->update(['is_published' => !$portfolio->is_published]);

        return back()->with('success', 'Published status updated.');
    }

    public function deleteImage(Portfolio $portfolio, PortfolioImage $image)
    {
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}
