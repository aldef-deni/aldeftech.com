<?php

namespace App\Http\Controllers;

use App\Models\CeoProfile;
use App\Models\Client;

class AboutController extends Controller
{
    public function index()
    {
        try {
            $ceoProfile = CeoProfile::active()->first();
        } catch (\Throwable $e) {
            $ceoProfile = null;
        }

        if (!$ceoProfile) {
            $ceoProfile = (object)[
                'name' => 'Deni Afrizal',
                'position' => 'Founder & Lead Technical Architect',
                'profile_photo' => null,
                'short_bio' => 'Berpengalaman merancang dan mengimplementasikan arsitektur software berskala enterprise, platform SaaS, serta otomatisasi cerdas untuk efisiensi bisnis.',
                'full_bio' => 'Deni Afrizal adalah praktisi software engineering dan technical architect dengan dedikasi mendalam pada perancangan sistem enterprise, scalable backend, dan integrasi kecerdasan buatan. Berfokus pada zero technical debt dan arsitektur yang tahan uji untuk percepatan pertumbuhan bisnis klien.',
                'skills' => ['System Architecture', 'Custom Software Engineering', 'SaaS Platform Development', 'Business Process Automation', 'AI & Machine Learning Integration', 'IT Project Management'],
            ];
        }

        $founderVideoPath = 'videos/CEO-aldeftech3.mp4';
        $founderVideoFile = public_path($founderVideoPath);

        // The same table that feeds the homepage marquee; the scope carries the
        // rule, so the two pages can never disagree about what is publishable.
        $clients = Client::forAbout()->displayOrder()->get();

        return view('pages.about', [
            'ceoProfile' => $ceoProfile,
            'clients' => $clients,
            'founderVideoUrl' => is_file($founderVideoFile)
                ? asset($founderVideoPath) . '?v=' . filemtime($founderVideoFile)
                : null,
        ]);
    }
}
