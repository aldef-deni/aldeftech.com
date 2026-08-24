<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SeoSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.seo');
    }

    public function update(Request $request)
    {
        $fields = [
            'seo_default_title', 'seo_default_description', 'seo_default_image',
        ];

        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''), 'text', 'seo');
        }

        return redirect()->route('admin.settings.seo')->with('success', 'SEO settings updated successfully.');
    }
}
