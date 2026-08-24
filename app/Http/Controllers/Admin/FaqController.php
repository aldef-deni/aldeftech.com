<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::ordered()->get();
        return view('admin.faq.index', ['faqs' => $faqs]);
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:5000',
            'category' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $faq = Faq::create($validated);
        ActivityLog::log('faq.created', "Created FAQ: \"{$faq->question}\"", $faq);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faq.edit', ['faq' => $faq]);
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string|max:5000',
            'category' => 'nullable|string|max:100',
            'is_published' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $faq->update($validated);
        ActivityLog::log('faq.updated', "Updated FAQ: \"{$faq->question}\"", $faq);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $question = $faq->question;
        $faq->delete();
        ActivityLog::log('faq.deleted', "Deleted FAQ: \"{$question}\"");

        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array', 'order.*' => 'integer|exists:faqs,id']);

        foreach ($request->input('order') as $index => $id) {
            Faq::where('id', $id)->update(['sort_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
