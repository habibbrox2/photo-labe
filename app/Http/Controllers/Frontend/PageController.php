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

        return view('frontend.cms-page', compact('page'));
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

        return view('frontend.cms-page', compact('page'));
    }

    public function system(string $key, string $fallback)
    {
        $page = Page::systemKey($key)->first();
        if (! $page) return view($fallback);
        abort_unless($page->status === 'published', 404);
        // Empty system records are intentional migration placeholders: preserve the
        // hand-built page until an editor adds its first structured block.
        if (empty($page->blocks) && in_array($key, [Page::SYSTEM_HOME, Page::SYSTEM_FAQ, Page::SYSTEM_PRICING], true)) return view($fallback);
        return view('frontend.cms-page', compact('page'));
    }
}
