<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SiteSetting;
use App\Services\StoreWhatsApp;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/** WhatsApp number Aldef Store sends enquiries to. */
class StoreSettingController extends Controller
{
    public function edit()
    {
        return view('admin.store.settings', [
            'storeNumber' => (string) SiteSetting::get(StoreWhatsApp::SETTING_KEY, ''),
            'businessNumber' => StoreWhatsApp::normalize((string) SiteSetting::get('whatsapp_number', '')),
            'effectiveNumber' => StoreWhatsApp::number(),
            'source' => StoreWhatsApp::source(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate(['store_whatsapp_number' => 'nullable|string|max:30']);

        $input = trim((string) $request->input('store_whatsapp_number', ''));
        $number = $input === '' ? '' : StoreWhatsApp::normalize($input);

        if ($number === null) {
            throw ValidationException::withMessages([
                'store_whatsapp_number' => 'Nomor tidak valid. Tulis dalam format internasional, misalnya 6281234567xxx (10–15 digit, bukan nomor contoh).',
            ]);
        }

        SiteSetting::set(StoreWhatsApp::SETTING_KEY, $number, 'text', 'store');
        ActivityLog::log('store.settings.updated', $number === '' ? 'Cleared store WhatsApp number' : 'Updated store WhatsApp number');

        return redirect()->route('admin.store.settings')->with('success', 'Pengaturan Aldef Store disimpan.');
    }
}
