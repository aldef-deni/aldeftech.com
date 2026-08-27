<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    use SavesTranslations;

    public function index()
    {
        $testimonials = Testimonial::ordered()->get();
        return view('admin.testimonials.index', ['testimonials' => $testimonials]);
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:500',
            'testimonial' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $testimonial = Testimonial::create($validated);
        $this->saveTranslations($request, $testimonial);
        ActivityLog::log('testimonial.created', "Created testimonial from \"{$testimonial->client_name}\"", $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', ['testimonial' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:500',
            'testimonial' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $testimonial->update($validated);
        $this->saveTranslations($request, $testimonial);
        ActivityLog::log('testimonial.updated', "Updated testimonial from \"{$testimonial->client_name}\"", $testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $name = $testimonial->client_name;
        $testimonial->delete();
        ActivityLog::log('testimonial.deleted', "Deleted testimonial from \"{$name}\"");

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:testimonials,id']);

        foreach ($request->input('order') as $index => $id) {
            Testimonial::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
