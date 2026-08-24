<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

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

        $lead = Lead::create($validated);

        // Log activity
        \App\Models\ActivityLog::log('lead.created', "New lead received from {$validated['name']} ({$validated['email']})", $lead);

        return redirect()->route('contact')
            ->with('success', 'Pesan Anda telah terkami. Kami akan segera menghubungi Anda!')
            ->with('whatsapp_url', WhatsAppService::getUrl());
    }
}
