<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\HomepageSection;
use App\Models\Portfolio;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Solution;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $hero = HomepageSection::getByKey('hero');
        $services = Service::published()->ordered()->limit(8)->get();
        $solutions = Solution::published()->ordered()->limit(10)->get();
        $portfolios = Portfolio::published()->featured()->ordered()->limit(3)->get();
        $processSteps = ProcessStep::published()->ordered()->get();
        // Featured first, then the editor's order. The locale scope keeps
        // untranslated quotes off the English page instead of leaking the
        // Indonesian original through the usual fallback.
        $testimonials = Testimonial::published()
            ->translatedIn(app()->getLocale())
            ->displayOrder()
            ->get();
        $latestPosts = BlogPost::published()->with('category')->latest('published_at')->limit(3)->get();
        $ceoProfile = \App\Models\CeoProfile::active()->first();
        $previewVideoPath = 'videos/aldeftech-preview.mp4';
        $previewVideoUrl = is_file(public_path($previewVideoPath))
            ? asset($previewVideoPath)
            : null;

        return view('pages.home', compact(
            'hero', 'services', 'solutions', 'portfolios',
            'processSteps', 'testimonials', 'latestPosts', 'ceoProfile', 'previewVideoUrl'
        ));
    }
}
