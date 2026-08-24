<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::published()->ordered()->get();

        return view('pages.services', ['services' => $services]);
    }
}
