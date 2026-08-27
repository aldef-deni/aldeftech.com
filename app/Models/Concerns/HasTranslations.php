<?php

namespace App\Models\Concerns;

/**
 * Serves per-locale copy for the fields listed in a model's $translatable.
 *
 * The base columns keep holding Indonesian, so nothing that already reads a
 * model changes behaviour on the default locale. When another locale is active
 * and a translation exists, it is returned instead; when it is missing or blank
 * the Indonesian value shows through, which is far better than an empty page.
 *
 *   $service->title          // locale-aware
 *   $service->getBase('title')          // always the stored Indonesian
 *   $service->translate('title', 'en')  // a specific locale, no fallback
 */
trait HasTranslations
{
    public function initializeHasTranslations(): void
    {
        $this->mergeCasts(['translations' => 'array']);
        $this->mergeFillable(['translations']);
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (! in_array($key, $this->translatable ?? [], true)) {
            return $value;
        }

        $locale = app()->getLocale();

        if ($locale === config('locales.default', 'id')) {
            return $value;
        }

        $translated = data_get($this->translations, "{$locale}.{$key}");

        return $this->isBlank($translated) ? $value : $translated;
    }

    /** The stored value, ignoring the active locale. */
    public function getBase(string $key)
    {
        return parent::getAttribute($key);
    }

    /** A specific locale's value, or null when it has not been written yet. */
    public function translate(string $key, string $locale)
    {
        $value = data_get($this->translations, "{$locale}.{$key}");

        return $this->isBlank($value) ? null : $value;
    }

    /**
     * Merge one locale's fields into the stored translations, dropping blanks so
     * an emptied admin field falls back rather than rendering as an empty string.
     */
    public function setTranslations(string $locale, array $fields): void
    {
        $all = $this->translations ?? [];
        $current = $all[$locale] ?? [];

        foreach ($fields as $key => $value) {
            if (! in_array($key, $this->translatable ?? [], true)) {
                continue;
            }

            if ($this->isBlank($value)) {
                unset($current[$key]);
                continue;
            }

            $current[$key] = $value;
        }

        if (empty($current)) {
            unset($all[$locale]);
        } else {
            $all[$locale] = $current;
        }

        $this->translations = $all ?: null;
    }

    private function isBlank($value): bool
    {
        if (is_array($value)) {
            return count(array_filter($value, fn ($v) => trim((string) $v) !== '')) === 0;
        }

        return trim((string) $value) === '';
    }
}
