<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with('assignee');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        if ($request->has('archived')) {
            $query->archived();
        } else {
            $query->active();
        }

        $leads = $query->latest()->paginate(20)->withQueryString();

        return view('admin.leads.index', ['leads' => $leads]);
    }

    public function show(Lead $lead)
    {
        $lead->load(['notes.user', 'assignee']);

        return view('admin.leads.show', ['lead' => $lead]);
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate(['status' => 'required|in:contacted,qualified,proposal,negotiation,won,lost']);

        $oldStatus = $lead->status_label;
        $lead->update(['status' => $request->input('status')]);

        ActivityLog::log('lead.status_changed', "Lead \"{$lead->name}\" status changed from {$oldStatus} to {$lead->status_label}", $lead);

        return back()->with('success', 'Lead status updated.');
    }

    public function addNote(Request $request, Lead $lead)
    {
        $request->validate(['note' => 'required|string|max:2000']);

        $lead->notes()->create([
            'user_id' => auth()->id(),
            'note' => $request->input('note'),
        ]);

        return back()->with('success', 'Note added.');
    }

    public function assign(Request $request, Lead $lead)
    {
        $request->validate(['assigned_to' => 'nullable|exists:users,id']);

        $lead->update(['assigned_to' => $request->input('assigned_to')]);

        return back()->with('success', 'Lead assigned.');
    }

    public function archive(Lead $lead)
    {
        $lead->update(['archived_at' => $lead->archived_at ? null : now()]);

        return back()->with('success', $lead->archived_at ? 'Lead archived.' : 'Lead restored.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    public function export(Request $request)
    {
        $query = Lead::query();

        if ($request->has('archived')) {
            $query->archived();
        } else {
            $query->active();
        }

        $leads = $query->latest()->get();

        $csv = "Name,Company,Email,WhatsApp,Project Type,Budget,Message,Status,Source,Created At\n";

        foreach ($leads as $lead) {
            $csv .= "\"{$lead->name}\",\"{$lead->company}\",\"{$lead->email}\",\"{$lead->whatsapp}\",";
            $csv .= "\"{$lead->project_type}\",\"{$lead->budget_range}\",\"{$lead->message}\",";
            $csv .= "\"{$lead->status_label}\",\"{$lead->source_label}\",\"{$lead->created_at}\"\n";
        }

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads-export.csv"',
        ]);
    }
}
