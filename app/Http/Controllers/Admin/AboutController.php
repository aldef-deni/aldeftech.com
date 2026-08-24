<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        return view('admin.about.edit');
    }

    public function update(Request $request)
    {
        $fields = [
            'about_title' => 'nullable|string|max:255',
            'about_subtitle' => 'nullable|string|max:500',
            'about_content' => 'nullable|string',
            'about_mission' => 'nullable|string|max:1000',
            'about_vision' => 'nullable|string|max:1000',
            'about_values' => 'nullable|array',
            'about_values.*' => 'string',
        ];

        $request->validate($fields);

        $fieldsToSave = array_keys($fields);
        foreach ($fieldsToSave as $field) {
            $value = $request->input($field);
            if ($field === 'about_values') {
                $value = json_encode($value);
            }
            SiteSetting::set($field, $value, 'textarea', 'about');
        }

        return redirect()->route('admin.about.edit')->with('success', 'About page updated successfully.');
    }
}
