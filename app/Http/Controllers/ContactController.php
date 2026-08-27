<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lead;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
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
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        try {
            $lead = Lead::create($validated);
            ActivityLog::log(
                'lead.created',
                "Lead baru dari {$validated['name']} ({$validated['email']})",
                $lead
            );
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

        return redirect()->route('contact')
            ->with('success', 'Brief Anda sudah kami terima. Tim kami akan menghubungi Anda dalam waktu dekat.')
            ->with('whatsapp_url', WhatsAppService::getUrl());
    }
}
