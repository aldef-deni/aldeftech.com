<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BacklinkProspect;
use App\Models\BlogPost;
use App\Models\MarketingContent;
use App\Models\SeoGrowthRun;
use App\Models\SeoOpportunity;
use App\Models\SeoOutreachDraft;
use App\Models\SeoPageReview;
use App\Services\SeoOutreachService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Throwable;

class SeoGrowthController extends Controller
{
    public function index(Request $request)
    {
        if (! Schema::hasTable('seo_outreach_drafts')) {
            return view('admin.seo-growth.index', ['ready' => false]);
        }
        $focus = $request->validate([
            'prospect_id' => 'nullable|integer|min:1', 'opportunity_id' => 'nullable|integer|min:1',
            'pack_id' => 'nullable|integer|min:1', 'run_id' => 'nullable|integer|min:1',
        ]);
        return view('admin.seo-growth.index', [
            'ready' => true,
            'activity' => app(\App\Services\SeoActivityService::class)->dashboard(),
            'stats' => [
                'Artikel terbit' => BlogPost::published()->count(),
                'Peluang konten' => SeoOpportunity::where('status', 'new')->count(),
                'Prospek backlink' => BacklinkProspect::count(),
                'Prospek disetujui' => BacklinkProspect::where('status', 'approved')->count(),
                'Draf outreach' => SeoOutreachDraft::where('status', 'draft')->count(),
                'Rekomendasi refresh' => SeoPageReview::where('optimization_status', 'review_needed')->count(),
            ],
            'prospects' => BacklinkProspect::with('post', 'outreach')->when($focus['prospect_id'] ?? null, fn ($q, $id) => $q->whereKey($id))->latest()->paginate(10, ['*'], 'prospects'),
            'opportunities' => SeoOpportunity::when($focus['opportunity_id'] ?? null, fn ($q, $id) => $q->whereKey($id))->orderByDesc('priority')->latest()->paginate(10, ['*'], 'opportunities'),
            'reviews' => SeoPageReview::with('post')->latest('analyzed_at')->paginate(10, ['*'], 'reviews'),
            'distributions' => MarketingContent::with('blogPost')->where('content_type', 'distribution')->when($focus['pack_id'] ?? null, fn ($q, $id) => $q->whereKey($id))->latest()->paginate(5, ['*'], 'packs'),
            'runs' => SeoGrowthRun::whereIn('task', ['daily', 'weekly'])->when($focus['run_id'] ?? null, fn ($q, $id) => $q->whereKey($id))->latest()->limit(8)->get(),
        ]);
    }

    public function prospect(Request $request, BacklinkProspect $prospect)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(BacklinkProspect::STATUSES)],
            'notes' => 'nullable|string|max:5000',
        ]);
        if ($data['status'] === 'approved' && ! $prospect->post?->isPublished()) {
            return back()->withErrors(['seo' => 'Artikel tujuan harus terbit sebelum prospek disetujui.']);
        }
        $prospect->update($data);
        return back()->with('success', 'Status prospek diperbarui.');
    }

    public function outreach(BacklinkProspect $prospect)
    {
        try {
            app(SeoOutreachService::class)->draft($prospect);
        } catch (Throwable $e) {
            return back()->withErrors(['seo' => 'Draf belum tersedia. Pastikan prospek disetujui, artikel terbit dan batas API belum tercapai.']);
        }
        return back()->with('success', 'Draf outreach siap ditinjau; belum dikirim.');
    }

    public function opportunity(Request $request, SeoOpportunity $opportunity)
    {
        $opportunity->update($request->validate(['status' => 'required|in:new,reviewed,used,rejected']));
        return back()->with('success', 'Status peluang diperbarui.');
    }

    public function review(Request $request, SeoPageReview $review)
    {
        $data = $request->validate([
            'target_keyword' => 'nullable|string|max:150',
            'optimization_status' => 'required|in:review_needed,reviewed',
        ]);
        if ($data['optimization_status'] === 'reviewed') {
            $data['next_review_at'] = now()->addDays((int) config('seo_growth.review_days', 90));
        }
        $review->update($data);
        return back()->with('success', 'Status review diperbarui. Isi artikel tidak diubah.');
    }
}
