<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::published()->ordered()->with('category')->get();
        $categories = PortfolioCategory::withCount('portfolios')->orderBy('sort_order')->get();

        return view('pages.portfolio', compact('portfolios', 'categories'));
    }

    public function show(Portfolio $portfolio)
    {
        if (!$portfolio->is_published) {
            abort(404);
        }

        $portfolio->load(['category', 'images']);
        $relatedPortfolios = Portfolio::published()
            ->where('category_id', $portfolio->category_id)
            ->where('id', '!=', $portfolio->id)
            ->with('category')
            ->limit(3)
            ->get();

        return view('pages.portfolio-show', ['portfolio' => $portfolio, 'relatedPortfolios' => $relatedPortfolios]);
    }
}
