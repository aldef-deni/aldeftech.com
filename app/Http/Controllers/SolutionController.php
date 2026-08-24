<?php

namespace App\Http\Controllers;

use App\Models\Solution;

class SolutionController extends Controller
{
    public function index()
    {
        $solutions = Solution::published()->ordered()->get();

        return view('pages.solutions', ['solutions' => $solutions]);
    }
}
