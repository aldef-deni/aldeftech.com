<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $links = SocialLink::ordered()->get();
        return view('admin.social-media.index', ['links' => $links]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        SocialLink::create($validated);

        return redirect()->route('admin.social-media.index')->with('success', 'Social link added.');
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $socialLink->update($validated);

        return redirect()->route('admin.social-media.index')->with('success', 'Social link updated.');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();

        return redirect()->route('admin.social-media.index')->with('success', 'Social link deleted.');
    }
}
