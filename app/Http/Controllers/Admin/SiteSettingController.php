<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.site');
    }

    public function update(Request $request)
    {
        $fields = [
            'site_name', 'site_tagline', 'site_description',
            'site_logo', 'site_favicon',
            'email', 'phone', 'address',
            'google_maps_url',
            'copyright',
            'footer_description',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $type = in_array($field, ['site_description', 'footer_description']) ? 'textarea' : 'text';
                SiteSetting::set($field, $request->input($field), $type, 'general');
            }
        }

        return redirect()->route('admin.settings.site')->with('success', 'Site settings updated successfully.');
    }
}
