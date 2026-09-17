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

    /**
     * Field rules shared by store() and update(): a quote is attributed to a
     * real person, so its length limits are deliberately tight.
     */
    private function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:500',
            'testimonial' => 'required|string|max:2000',
            'rating' => 'required|integer|min:1|max:5',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ];
    }

    /**
     * A published testimonial always carries a date: the editor's own if they
     * picked one (a future date schedules it), otherwise now. A draft keeps no
     * date, so re-publishing later does not surface a stale timestamp.
     */
    private function publication(Request $request, array $validated): array
    {
        $published = $request->boolean('is_published');

        $validated['is_published'] = $published;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['published_at'] = $published
            ? ($validated['published_at'] ?? now())
            : null;

        return $validated;
    }

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
        $validated = $this->publication($request, $request->validate($this->rules()));

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
        $validated = $this->publication($request, $request->validate($this->rules()));

        // A blank date field means "keep what is there": re-saving must not move
        // a scheduled testimonial to today, nor restamp a published one.
        if ($validated['is_published'] && ! filled($request->input('published_at')) && $testimonial->published_at) {
            $validated['published_at'] = $testimonial->published_at;
        }

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

    /**
     * Publish / unpublish from the list. Publishing stamps a date when the row
     * has none, because the public query requires one.
     */
    public function togglePublished(Testimonial $testimonial)
    {
        $publishing = ! $testimonial->is_published;

        $testimonial->update([
            'is_published' => $publishing,
            'published_at' => $publishing ? ($testimonial->published_at ?? now()) : $testimonial->published_at,
        ]);

        ActivityLog::log(
            $publishing ? 'testimonial.published' : 'testimonial.unpublished',
            ($publishing ? 'Published' : 'Unpublished') . " testimonial from \"{$testimonial->client_name}\"",
            $testimonial
        );

        return back()->with('success', $publishing
            ? 'Testimoni diterbitkan.'
            : 'Testimoni dikembalikan menjadi draf.');
    }

    public function toggleFeatured(Testimonial $testimonial)
    {
        $testimonial->update(['is_featured' => ! $testimonial->is_featured]);

        ActivityLog::log('testimonial.featured', "Updated featured flag on testimonial from \"{$testimonial->client_name}\"", $testimonial);

        return back()->with('success', $testimonial->is_featured
            ? 'Testimoni ditandai unggulan.'
            : 'Tanda unggulan dilepas.');
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
