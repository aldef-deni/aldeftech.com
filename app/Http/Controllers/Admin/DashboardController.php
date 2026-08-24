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

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'portfolios' => Portfolio::count(),
            'services' => Service::count(),
            'solutions' => Solution::count(),
            'blog_posts' => BlogPost::count(),
            'testimonials' => Testimonial::count(),
            'faqs' => Faq::count(),
        ];

        $recentLeads = Lead::latest()->limit(10)->get();
        $recentActivity = ActivityLog::with('user')->latest()->limit(15)->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'recentActivity'));
    }
}
