<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order')->get();
        $sectionKeys = ['hero', 'trust', 'services', 'featured_portfolio', 'why_aldeftech', 'process', 'ceo', 'testimonials', 'faq', 'final_cta'];

        foreach ($sectionKeys as $key) {
            if (!$sections->where('section_key', $key)->first()) {
                HomepageSection::create([
                    'section_key' => $key,
                    'title' => ucfirst(str_replace('_', ' ', $key)),
                    'is_visible' => true,
                    'sort_order' => array_search($key, $sectionKeys),
                ]);
            }
        }

        $sections = HomepageSection::orderBy('sort_order')->get();

        return view('admin.homepage.index', ['sections' => $sections]);
    }

    public function update(Request $request, HomepageSection $section)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'is_visible' => 'boolean',
        ]);

        $content = $request->input('content', []);

        $section->update([
            'title' => $validated['title'] ?? $section->title,
            'subtitle' => $validated['subtitle'] ?? $section->subtitle,
            'content' => $content,
            'is_visible' => $request->boolean('is_visible', true),
        ]);

        return redirect()->route('admin.homepage.index')->with('success', 'Section updated successfully.');
    }
}
