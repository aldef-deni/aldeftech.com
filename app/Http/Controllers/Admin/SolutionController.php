<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Solution;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function index()
    {
        $solutions = Solution::ordered()->get();
        return view('admin.solutions.index', ['solutions' => $solutions]);
    }

    public function create()
    {
        return view('admin.solutions.create');
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

        $solution = Solution::create($validated);
        ActivityLog::log('solution.created', "Created solution \"{$solution->title}\"", $solution);

        return redirect()->route('admin.solutions.index')->with('success', 'Solution created successfully.');
    }

    public function edit(Solution $solution)
    {
        return view('admin.solutions.edit', ['solution' => $solution]);
    }

    public function update(Request $request, Solution $solution)
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

        $solution->update($validated);
        ActivityLog::log('solution.updated', "Updated solution \"{$solution->title}\"", $solution);

        return redirect()->route('admin.solutions.index')->with('success', 'Solution updated successfully.');
    }

    public function destroy(Solution $solution)
    {
        $title = $solution->title;
        $solution->delete();
        ActivityLog::log('solution.deleted', "Deleted solution \"{$title}\"");

        return redirect()->route('admin.solutions.index')->with('success', 'Solution deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:solutions,id']);

        foreach ($request->input('order') as $index => $id) {
            Solution::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
