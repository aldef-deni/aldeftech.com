<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PageSeo;
use Illuminate\Http\Request;

class PageSeoController extends Controller
{
    public function index()
    {
        $rows = PageSeo::all()->groupBy('route_name');

        return view('admin.settings.page-seo.index', [
            'pages' => PageSeo::managedPages(),
            'rows' => $rows,
            'locales' => config('locales.available', []),
        ]);
    }

    public function edit(string $page)
    {
        abort_unless(array_key_exists($page, PageSeo::managedPages()), 404);

        return view('admin.settings.page-seo.edit', [
            'page' => $page,
            'label' => PageSeo::managedPages()[$page],
            'locales' => config('locales.available', []),
            'entries' => PageSeo::where('route_name', $page)->get()->keyBy('locale'),
        ]);
    }

    public function update(Request $request, string $page)
    {
        abort_unless(array_key_exists($page, PageSeo::managedPages()), 404);

        $locales = array_keys(config('locales.available', []));

        $validated = $request->validate([
            'seo' => 'array',
            'seo.*.meta_title' => 'nullable|string|max:255',
            'seo.*.meta_description' => 'nullable|string|max:500',
            'seo.*.og_image' => 'nullable|string|max:500',
            'seo.*.noindex' => 'nullable|boolean',
        ]);

        foreach ($validated['seo'] ?? [] as $locale => $fields) {
            if (! in_array($locale, $locales, true)) {
                continue;
            }

            PageSeo::updateOrCreate(
                ['route_name' => $page, 'locale' => $locale],
                [
                    'meta_title' => $fields['meta_title'] ?? null,
                    'meta_description' => $fields['meta_description'] ?? null,
                    'og_image' => $fields['og_image'] ?? null,
                    'noindex' => (bool) ($fields['noindex'] ?? false),
                ]
            );
        }

        PageSeo::clearCache();
        ActivityLog::log('page_seo.updated', "Updated page SEO for \"{$page}\"");

        return redirect()->route('admin.settings.page-seo.index')
            ->with('success', 'Meta halaman berhasil disimpan.');
    }
}
