<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\CeoProfile;
use Illuminate\Http\Request;

class CeoProfileController extends Controller
{
    use SavesTranslations;

    public function edit()
    {
        $profile = CeoProfile::firstOrCreate([], [
            'name' => 'Deni Afrizal',
            'position' => 'CEO & System/Application Developer',
            'is_active' => true,
        ]);

        return view('admin.ceo.edit', ['profile' => $profile]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'profile_photo' => 'nullable|string|max:500',
            'short_bio' => 'nullable|string|max:1000',
            'full_bio' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            'experience' => 'nullable|array',
            'experience.*' => 'string',
            'linkedin' => 'nullable|url|max:500',
            'github' => 'nullable|url|max:500',
            'instagram' => 'nullable|url|max:500',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['skills'] = $request->input('skills', []);
        $validated['experience'] = $request->input('experience', []);
        $validated['is_active'] = $request->boolean('is_active', true);

        $profile = CeoProfile::first();
        if ($profile) {
            $profile->update($validated);
        } else {
            $profile = CeoProfile::create($validated);
        }

        $this->saveTranslations($request, $profile);

        return redirect()->route('admin.ceo.edit')->with('success', 'CEO profile updated successfully.');
    }
}
