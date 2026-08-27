<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;

trait SavesTranslations
{
    /**
     * Persist the translations[locale][field] block that the admin forms post.
     *
     * Unknown locales are ignored so a hand-crafted request cannot write junk
     * into the JSON column; blank fields are dropped by setTranslations() so the
     * record falls back to Indonesian rather than rendering an empty string.
     */
    protected function saveTranslations(Request $request, $model): void
    {
        $allowed = array_keys(config('locales.available', []));
        $default = config('locales.default', 'id');

        foreach ((array) $request->input('translations', []) as $locale => $fields) {
            if ($locale === $default || ! in_array($locale, $allowed, true) || ! is_array($fields)) {
                continue;
            }

            $model->setTranslations($locale, $fields);
        }

        $model->save();
    }
}
