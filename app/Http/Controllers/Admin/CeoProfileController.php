<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\CeoProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class CeoProfileController extends Controller
{
    use SavesTranslations;

    /**
     * Old input is flashed under the name of the form that submitted it.
     *
     * Both leadership forms post the same field names (name, position, skills,
     * translations...), so an unscoped `old()` would repopulate the other profile
     * with the wrong person's data the moment one form failed validation. The key
     * below keeps each form's repopulated values to itself.
     */
    private const FORM_CEO = 'ceo';
    private const FORM_COMMISSIONER = 'komisaris';

    /** The CEO profile on top, the commissioner profile at the bottom. */
    public function edit(Request $request): View
    {
        return view('admin.ceo.edit', [
            'ceo' => $this->formProfile($request, CeoProfile::ROLE_CEO, self::FORM_CEO),
            'commissioner' => $this->formProfile($request, CeoProfile::ROLE_COMMISSIONER, self::FORM_COMMISSIONER),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        return $this->persist($request, CeoProfile::ROLE_CEO, self::FORM_CEO);
    }

    public function updateCommissioner(Request $request): RedirectResponse
    {
        return $this->persist($request, CeoProfile::ROLE_COMMISSIONER, self::FORM_COMMISSIONER);
    }

    /**
     * What one form should render: the stored row for that role (or its defaults)
     * with the values this editor just submitted layered on top in memory, so a
     * validation error does not throw their work away. Nothing is saved here.
     */
    private function formProfile(Request $request, string $role, string $scope): CeoProfile
    {
        $profile = CeoProfile::forRole($role);
        $submitted = $request->old($scope);

        if (! is_array($submitted)) {
            return $profile;
        }

        $profile->fill(Arr::except($submitted, ['translations', '_token', '_method']));

        foreach ((array) ($submitted['translations'] ?? []) as $locale => $fields) {
            if (is_string($locale) && is_array($fields)) {
                $profile->setTranslations($locale, $fields);
            }
        }

        return $profile;
    }

    /**
     * Validation and persistence for exactly one role.
     *
     * The role is part of the lookup (`updateOrCreate(['role' => ...])`), so saving
     * the commissioner can only ever write the commissioner row and saving the CEO
     * can only ever write the CEO row — including its translations.
     */
    private function persist(Request $request, string $role, string $scope): RedirectResponse
    {
        $request->merge([
            'skills' => $this->cleanList($request->input('skills')),
            'experience' => $this->cleanList($request->input('experience')),
            'translations' => $this->cleanTranslationLists($request->input('translations')),
        ]);

        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return redirect()->route('admin.ceo.edit')
                ->withInput([$scope => Arr::except($request->all(), ['_token', '_method'])])
                ->withErrors($validator);
        }

        $validated = $validator->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $profile = CeoProfile::updateOrCreate(['role' => $role], $validated);

        $this->saveTranslations($request, $profile);

        return redirect()->route('admin.ceo.edit')->with('success',
            $role === CeoProfile::ROLE_COMMISSIONER
                ? 'Profil komisaris berhasil disimpan.'
                : 'Profil CEO berhasil disimpan.');
    }

    /**
     * The English list fields sit one level deeper and need the same treatment,
     * otherwise a stray nested value would be stored in the translations column.
     */
    private function cleanTranslationLists(mixed $translations): array
    {
        $translations = (array) $translations;

        foreach ($translations as $locale => $fields) {
            if (! is_array($fields)) {
                continue;
            }

            foreach (['skills', 'experience'] as $list) {
                if (array_key_exists($list, $fields)) {
                    $fields[$list] = $this->cleanList($fields[$list]);
                }
            }

            $translations[$locale] = $fields;
        }

        return $translations;
    }

    /**
     * A flat list of trimmed, non-empty strings.
     *
     * The list widget always posts at least one (usually blank) row, and a hand
     * built request could post anything at all. Normalising here means an empty
     * or unexpected shape can never reach the validator as "must be a string",
     * and blank rows never end up stored as empty chips on the website.
     */
    private function cleanList(mixed $value): array
    {
        $items = [];

        foreach (Arr::flatten((array) $value) as $item) {
            if (! is_scalar($item)) {
                continue;
            }

            $item = trim((string) $item);

            if ($item !== '') {
                $items[] = $item;
            }
        }

        return $items;
    }
}
