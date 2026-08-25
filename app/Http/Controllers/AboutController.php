<?php

namespace App\Http\Controllers;

use App\Models\CeoProfile;

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

        return view('pages.about', ['ceoProfile' => $ceoProfile]);
    }
}
