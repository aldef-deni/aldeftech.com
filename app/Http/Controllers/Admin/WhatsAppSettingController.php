<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class WhatsAppSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.whatsapp');
    }

    public function update(Request $request)
    {
        $fields = ['whatsapp_number', 'whatsapp_default_message'];

        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''), 'text', 'whatsapp');
        }

        return redirect()->route('admin.settings.whatsapp')->with('success', 'WhatsApp settings updated successfully.');
    }
}
