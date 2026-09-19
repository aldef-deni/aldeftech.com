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
        // One page, one menu entry: the CEO profile as before, with the
        // commissioner profile appended underneath.
        $profile = CeoProfile::forRole(CeoProfile::ROLE_CEO);
        $commissioner = CeoProfile::forRole(CeoProfile::ROLE_COMMISSIONER);

        return view('admin.ceo.edit', [
            'profile' => $profile,
            'commissioner' => $commissioner,
        ]);
    }

    public function update(Request $request)
    {
        $this->persist($request, CeoProfile::forRole(CeoProfile::ROLE_CEO));

        return redirect()->route('admin.ceo.edit')->with('success', 'CEO profile updated successfully.');
    }

    public function updateCommissioner(Request $request)
    {
        $this->persist($request, CeoProfile::forRole(CeoProfile::ROLE_COMMISSIONER));

        return redirect()->route('admin.ceo.edit')->with('success', 'Commissioner profile updated successfully.');
    }

    /** Validation and persistence shared by every leadership profile. */
    private function persist(Request $request, CeoProfile $profile): void
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

        $profile->update($validated);

        $this->saveTranslations($request, $profile);
    }
}
