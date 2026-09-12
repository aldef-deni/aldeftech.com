<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Services\SpamScorer;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function thankYou()
    {
        return view('pages.contact-thank-you', [
            'leadConversion' => session()->pull('lead_conversion'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'whatsapp' => 'nullable|string|max:50',
            'project_type' => 'nullable|string|max:100',
            'budget_range' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
            'utm_source' => 'nullable|string|max:160',
            'utm_medium' => 'nullable|string|max:160',
            'utm_campaign' => 'nullable|string|max:160',
            'utm_term' => 'nullable|string|max:160',
            'utm_content' => 'nullable|string|max:160',
            'gclid' => 'nullable|string|max:160',
            'gbraid' => 'nullable|string|max:160',
            'wbraid' => 'nullable|string|max:160',
            'fbclid' => 'nullable|string|max:160',
            'landing_page' => 'nullable|string|max:500',
            'referrer' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        /*
         * Spam handling. Nothing is rejected: a flagged brief is still stored,
         * just filed away from the working list. Silently dropping a submission
         * would eventually lose a real enquiry, and the visitor would never know.
         */
        $secondsOnForm = null;
        try {
            $startedAt = decrypt($request->input('form_started_at'));
            $secondsOnForm = max(0, now()->timestamp - (int) $startedAt);
        } catch (\Throwable $e) {
            // Missing or tampered timing field: skip the signal rather than
            // punish a visitor whose session simply expired.
        }

        $assessment = app(SpamScorer::class)->score(
            $validated,
            $secondsOnForm,
            filled($request->input('website_url'))
        );

        $validated['ip_address'] = $request->ip();
        $validated['spam_score'] = $assessment['score'];
        $validated['spam_reasons'] = $assessment['reasons'];
        $validated['is_spam'] = app(SpamScorer::class)->isSpam($assessment['score']);
        $utmSource = Str::lower((string) ($validated['utm_source'] ?? ''));
        $validated['source'] = match (true) {
            filled($validated['gclid'] ?? null) || str_contains($utmSource, 'google') => 'google',
            filled($validated['fbclid'] ?? null) || str_contains($utmSource, 'facebook') || str_contains($utmSource, 'meta') => 'facebook',
            str_contains($utmSource, 'instagram') => 'instagram',
            str_contains($utmSource, 'linkedin') => 'linkedin',
            str_contains($utmSource, 'referral') => 'referral',
            default => 'website',
        };

        try {
            $lead = Lead::create($validated);
            if (! $lead->is_spam) {
                ActivityLog::log(
                    'lead.created',
                    "Lead baru dari {$validated['name']} ({$validated['email']})",
                    $lead
                );
            }
        } catch (\Throwable $e) {
            // Never tell a visitor the brief was received when it was not — a
            // silently dropped lead is a lost sale. Record it and hand them a
            // channel that does not depend on our database.
            Log::error('Gagal menyimpan lead dari formulir kontak.', [
                'exception' => $e->getMessage(),
                'email' => $validated['email'] ?? null,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Maaf, pesan Anda gagal tersimpan karena kendala teknis. Silakan hubungi kami langsung lewat WhatsApp agar tidak tertunda.')
                ->with('whatsapp_url', WhatsAppService::getUrl(
                    'Halo Aldef Tech, saya mencoba mengirim brief lewat website tetapi gagal terkirim. Berikut kebutuhan saya:'
                ));
        }

        $conversion = [
            'id' => (string) Str::uuid(),
            'form_name' => 'project_brief',
            'lead_source' => $lead->source,
            'project_type' => $lead->project_type,
            'budget_range' => $lead->budget_range,
        ];
        foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gbraid', 'wbraid', 'fbclid'] as $field) {
            $conversion[$field] = $lead->{$field};
        }

        return redirect(lroute('contact.thank-you'))
            ->with('lead_conversion', $conversion);
    }
}
