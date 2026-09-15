<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('frontend.page', compact('page'));
    }

    /**
     * The hand-written /about route, served from the CMS page an editor manages in
     * the admin (Pages → About). Kept deliberately resilient: until that page exists
     * it falls back to the built-in copy, and drafting it takes it offline like any
     * other page.
     */
    public function about()
    {
        $page = Page::systemKey(Page::SYSTEM_ABOUT)->first();

        if (! $page) {
            return view('frontend.about');
        }

        abort_unless($page->status === 'published', 404);

        return view('frontend.page', compact('page'));
    }
}
