<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MarketingAudience;
use App\Models\MarketingCampaign;
use App\Models\MarketingContent;
use App\Models\MarketingContentIdea;
use App\Services\Marketing\MarketingContentGeneratorService;
use App\Services\Marketing\MarketingIntelligenceService;
use App\Services\Marketing\MarketingPublisherService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Throwable;

class MarketingController extends Controller
{
    public function index()
    {
        $campaigns = MarketingCampaign::query()
            ->withCount(['contentIdeas'])
            ->orderByDesc('priority')
            ->latest()
            ->get();

        $recentContents = MarketingContent::query()
            ->with(['campaign', 'idea.audience', 'blogPost'])
            ->latest('generated_at')
            ->limit(8)
            ->get();

        $stats = [
            'campaigns' => MarketingCampaign::count(),
            'active_campaigns' => MarketingCampaign::where('status', 'active')->count(),
            'ideas' => MarketingContentIdea::count(),
            'drafts' => MarketingContent::where('status', 'draft')->count(),
            'published' => MarketingContent::where('status', 'published')->count(),
        ];

        return view('admin.marketing.index', compact(
            'campaigns',
            'recentContents',
            'stats'
        ));
    }

    public function create()
    {
        $audiences = MarketingAudience::query()
            ->where('is_active', true)
            ->orderByDesc('priority')
            ->get();

        return view('admin.marketing.create', [
            'audiences' => $audiences,
            'platforms' => $this->platformOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'objective' => 'nullable|string|max:1000',
            'target_audiences' => 'nullable|array',
            'target_audiences.*' => 'integer|exists:marketing_audiences,id',
            'platforms' => 'nullable|array',
            'platforms.*' => ['string', Rule::in(array_keys($this->platformOptions()))],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,paused,completed',
            'priority' => 'nullable|integer|min:0|max:100',
        ]);

        $campaign = MarketingCampaign::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueCampaignSlug($validated['name']),
            'description' => $validated['description'] ?? null,
            'objective' => $validated['objective'] ?? null,
            'target_audiences' => $validated['target_audiences'] ?? [],
            'platforms' => $validated['platforms'] ?? ['blog', 'linkedin', 'facebook', 'instagram'],
            'funnel_strategy' => [
                'awareness' => 'Edukasi masalah bisnis dan peluang digitalisasi.',
                'consideration' => 'Bangun trust melalui solusi, insight teknis, dan portfolio.',
                'decision' => 'Arahkan calon klien ke konsultasi dan discovery session.',
            ],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
            'priority' => $validated['priority'] ?? 50,
        ]);

        ActivityLog::log(
            'marketing.campaign.created',
            "Created AI marketing campaign \"{$campaign->name}\"",
            $campaign
        );

        return redirect()
            ->route('admin.marketing.show', $campaign)
            ->with('success', 'AI marketing campaign created.');
    }

    public function show(MarketingCampaign $campaign)
    {
        $campaign->load([
            'contentIdeas' => fn ($query) => $query
                ->with(['audience', 'painPoint', 'keyword', 'contentPillar', 'contents.blogPost'])
                ->orderByDesc('priority')
                ->latest(),
        ]);

        $contents = MarketingContent::query()
            ->with(['idea.audience', 'idea.contentPillar', 'blogPost'])
            ->where('marketing_campaign_id', $campaign->id)
            ->latest('generated_at')
            ->get();

        return view('admin.marketing.show', compact('campaign', 'contents'));
    }

    public function generateIdeas(
        Request $request,
        MarketingCampaign $campaign,
        MarketingIntelligenceService $intelligence
    ) {
        $validated = $request->validate([
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $ideas = $intelligence->generateIdeas(
            $campaign,
            $validated['limit'] ?? 12
        );

        ActivityLog::log(
            'marketing.ideas.generated',
            "Generated {$ideas->count()} AI marketing ideas for \"{$campaign->name}\"",
            $campaign
        );

        return back()->with('success', $ideas->count() . ' content ideas prepared.');
    }

    public function generateContent(
        MarketingContentIdea $idea,
        MarketingContentGeneratorService $generator
    ) {
        if ($idea->contents()->exists()) {
            return back()->with('error', 'Content for this idea already exists.');
        }

        try {
            $content = $generator->generate($idea);
        } catch (Throwable $e) {
            return back()->with('error', 'AI generation failed: ' . $e->getMessage());
        }

        ActivityLog::log(
            'marketing.content.generated',
            "Generated AI marketing content \"{$content->title}\"",
            $content
        );

        return back()->with('success', 'AI content generated as draft.');
    }

    public function approveContent(MarketingContent $content)
    {
        $content->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        ActivityLog::log(
            'marketing.content.approved',
            "Approved AI marketing content \"{$content->title}\"",
            $content
        );

        return back()->with('success', 'Content approved.');
    }

    public function publishContent(
        MarketingContent $content,
        MarketingPublisherService $publisher
    ) {
        try {
            $post = $publisher->publishToBlog($content, auth()->id());
        } catch (Throwable $e) {
            return back()->with('error', 'Publish failed: ' . $e->getMessage());
        }

        ActivityLog::log(
            'marketing.content.published',
            "Published AI marketing content \"{$content->title}\" to blog",
            $post
        );

        return back()->with('success', 'Content published to blog.');
    }

    protected function platformOptions(): array
    {
        return [
            'blog' => 'Blog SEO',
            'linkedin' => 'LinkedIn',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'whatsapp' => 'WhatsApp Business',
            'email' => 'Email',
            'community' => 'Komunitas/Forum',
        ];
    }

    protected function uniqueCampaignSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'marketing-campaign';
        $slug = $base;
        $counter = 2;

        while (MarketingCampaign::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
