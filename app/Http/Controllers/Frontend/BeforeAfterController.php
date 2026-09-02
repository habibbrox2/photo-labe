<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfterProject;

class BeforeAfterController extends Controller
{
    public function index()
    {
        $items = BeforeAfterProject::active()->ordered()->get();

        return view('frontend.before-after', compact('items'));
    }
}
