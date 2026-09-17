<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Client logos: the names shown in the homepage marquee and the About grid.
 *
 * Follows the same shape as the testimonial and portfolio screens — one
 * validation rule set, one publication flag, an activity log entry per write —
 * so the dashboard behaves identically wherever an editor lands.
 */
class ClientController extends Controller
{
    /**
     * Raster formats only. The shared uploader also accepts SVG, and this project
     * has no SVG sanitiser, so an SVG logo could carry script into a public page.
     * The mime type recorded by the uploader at upload time is what proves the
     * file really is an image, rather than trusting the extension on the path.
     */
    private const LOGO_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/avif'];

    private const LOGO_MESSAGES = [
        'logo.required' => 'Logo wajib diunggah.',
        'logo.exists' => 'Logo harus berupa file gambar yang diunggah lewat kolom ini (PNG, JPG, WEBP, atau GIF). SVG tidak didukung.',
    ];

    /**
     * A logo is what the sections render, so it is required when the client is
     * created. On update it may be left alone, which keeps the editor from
     * re-uploading an image just to fix a typo.
     */
    private function rules(bool $creating): array
    {
        return [
            'name' => 'required|string|max:255',
            'logo' => [
                $creating ? 'required' : 'nullable',
                'string',
                'max:500',
                Rule::exists('media', 'path')->where(
                    fn ($query) => $query->whereIn('mime_type', self::LOGO_MIME_TYPES)
                ),
            ],
            'website_url' => 'nullable|url|max:255',
            'is_published' => 'boolean',
            'show_home' => 'boolean',
            'show_about' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ];
    }

    /**
     * "example.com" is what an editor types; the url rule wants a scheme. Fill
     * one in before validating rather than rejecting a perfectly good address.
     */
    private function normalize(Request $request): void
    {
        $website = trim((string) $request->input('website_url'));

        if ($website !== '' && ! preg_match('#^[a-z][a-z0-9+.-]*://#i', $website)) {
            $website = 'https://' . ltrim($website, '/');
        }

        $request->merge(['website_url' => $website === '' ? null : $website]);
    }

    public function index()
    {
        $clients = Client::displayOrder()->get();

        return view('admin.clients.index', [
            'clients' => $clients,
            'onHome' => $clients->where('is_published', true)->where('show_home', true)->whereNotNull('logo')->count(),
            'onAbout' => $clients->where('is_published', true)->where('show_about', true)->whereNotNull('logo')->count(),
        ]);
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $this->normalize($request);
        $validated = $request->validate($this->rules(true), self::LOGO_MESSAGES);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_home'] = $request->boolean('show_home');
        $validated['show_about'] = $request->boolean('show_about');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $client = Client::create($validated);

        ActivityLog::log('client.created', "Created client \"{$client->name}\"", $client);

        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', ['client' => $client]);
    }

    public function update(Request $request, Client $client)
    {
        $this->normalize($request);
        $validated = $request->validate($this->rules(false), self::LOGO_MESSAGES);

        // An untouched uploader posts nothing, which must not erase the logo.
        if (! filled($validated['logo'] ?? null)) {
            unset($validated['logo']);
        }

        $validated['is_published'] = $request->boolean('is_published');
        $validated['show_home'] = $request->boolean('show_home');
        $validated['show_about'] = $request->boolean('show_about');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        $client->update($validated);

        ActivityLog::log('client.updated', "Updated client \"{$client->name}\"", $client);

        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil diperbarui.');
    }

    public function destroy(Client $client)
    {
        $name = $client->name;
        $client->delete();

        ActivityLog::log('client.deleted', "Deleted client \"{$name}\"");

        return redirect()->route('admin.clients.index')->with('success', 'Klien berhasil dihapus.');
    }

    /** Active / inactive from the list, mirroring the other content screens. */
    public function toggleActive(Client $client)
    {
        $activating = ! $client->is_published;

        $client->update(['is_published' => $activating]);

        ActivityLog::log(
            $activating ? 'client.activated' : 'client.deactivated',
            ($activating ? 'Activated' : 'Deactivated') . " client \"{$client->name}\"",
            $client
        );

        return back()->with('success', $activating
            ? 'Klien diaktifkan.'
            : 'Klien dinonaktifkan dan tidak tampil di website.');
    }
}
