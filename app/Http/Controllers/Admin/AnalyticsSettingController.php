<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AnalyticsSettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.analytics');
    }

    public function update(Request $request)
    {
        $fields = [
            'google_analytics_id', 'google_tag_manager_id',
            'meta_pixel_id', 'google_search_console_verification',
        ];

        foreach ($fields as $field) {
            SiteSetting::set($field, $request->input($field, ''), 'text', 'analytics');
        }

        return redirect()->route('admin.settings.analytics')->with('success', 'Analytics settings updated successfully.');
    }
}
