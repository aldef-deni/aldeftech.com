<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Lead;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Solution;
use App\Models\Testimonial;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'won_leads' => Lead::where('status', 'won')->count(),
            'portfolios' => Portfolio::count(),
            'services' => Service::count(),
            'solutions' => Solution::count(),
            'blog_posts' => BlogPost::count(),
            'testimonials' => Testimonial::count(),
            'faqs' => Faq::count(),
        ];

        // Content that is saved but not visible to the public yet — the most
        // common "why isn't it on the site?" question.
        $unpublished = [
            'services' => Service::where('is_published', false)->count(),
            'solutions' => Solution::where('is_published', false)->count(),
            'portfolios' => Portfolio::where('is_published', false)->count(),
            'testimonials' => Testimonial::where('is_published', false)->count(),
            'faqs' => Faq::where('is_published', false)->count(),
            'blog_posts' => BlogPost::where('status', '!=', 'published')->count(),
        ];
        $unpublishedTotal = array_sum($unpublished);

        $leadsByStatus = Lead::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->all();

        // Last 14 days of inbound leads, zero-filled so the sparkline has no gaps.
        $since = Carbon::today()->subDays(13);
        $rawTrend = Lead::where('created_at', '>=', $since)
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('count(*) as total'))
            ->groupBy('day')
            ->pluck('total', 'day')
            ->all();

        $leadTrend = [];
        for ($i = 0; $i < 14; $i++) {
            $date = $since->copy()->addDays($i);
            $key = $date->toDateString();
            $leadTrend[] = [
                'label' => $date->translatedFormat('d M'),
                'value' => (int) ($rawTrend[$key] ?? 0),
            ];
        }

        $recentLeads = Lead::with('assignee')->latest()->limit(8)->get();
        $recentActivity = ActivityLog::with('user')->latest()->limit(10)->get();

        return view('admin.dashboard', compact(
            'stats',
            'unpublished',
            'unpublishedTotal',
            'leadsByStatus',
            'leadTrend',
            'recentLeads',
            'recentActivity'
        ));
    }
}
