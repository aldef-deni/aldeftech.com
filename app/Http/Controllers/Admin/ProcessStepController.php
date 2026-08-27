<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\SavesTranslations;
use App\Http\Controllers\Controller;
use App\Models\ProcessStep;
use Illuminate\Http\Request;

class ProcessStepController extends Controller
{
    use SavesTranslations;

    public function index()
    {
        $steps = ProcessStep::ordered()->get();
        return view('admin.process-steps.index', ['steps' => $steps]);
    }

    public function create()
    {
        $nextNumber = ProcessStep::max('step_number') + 1;
        return view('admin.process-steps.create', ['nextNumber' => $nextNumber]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $this->saveTranslations($request, ProcessStep::create($validated));

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step created successfully.');
    }

    public function edit(ProcessStep $processStep)
    {
        return view('admin.process-steps.edit', ['step' => $processStep]);
    }

    public function update(Request $request, ProcessStep $processStep)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'icon' => 'nullable|string|max:100',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $processStep->update($validated);
        $this->saveTranslations($request, $processStep);

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step updated successfully.');
    }

    public function destroy(ProcessStep $processStep)
    {
        $processStep->delete();

        return redirect()->route('admin.process-steps.index')->with('success', 'Process step deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:process_steps,id']);

        foreach ($request->input('order') as $index => $id) {
            ProcessStep::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
