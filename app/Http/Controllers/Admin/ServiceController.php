<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::ordered()->get();
        return view('admin.services.index', ['services' => $services]);
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'sort_order' => 'integer|min:0',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['features'] = $request->input('features', []);
        $validated['is_published'] = $request->boolean('is_published');

        $service = Service::create($validated);
        ActivityLog::log('service.created', "Created service \"{$service->title}\"", $service);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', ['service' => $service]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'sort_order' => 'integer|min:0',
            'is_published' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $validated['features'] = $request->input('features', []);
        $validated['is_published'] = $request->boolean('is_published');

        $service->update($validated);
        ActivityLog::log('service.updated', "Updated service \"{$service->title}\"", $service);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $title = $service->title;
        $service->delete();
        ActivityLog::log('service.deleted', "Deleted service \"{$title}\"");

        return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:services,id']);

        foreach ($request->input('order') as $index => $id) {
            Service::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
