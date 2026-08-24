<?php

namespace App\Http\Controllers;

use App\Models\CeoProfile;

class AboutController extends Controller
{
    public function index()
    {
        $ceoProfile = CeoProfile::active()->first();

        return view('pages.about', ['ceoProfile' => $ceoProfile]);
    }
}
