<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PublicSiteController extends Controller
{
    public function index()
    {
        $keys = [Page::SYSTEM_HOME, Page::SYSTEM_ABOUT, Page::SYSTEM_FAQ, Page::SYSTEM_PRICING, Page::SYSTEM_CONTACT];
        $systemPages = Page::whereIn('system_key', $keys)->get()->keyBy('system_key');
        $customPages = Page::whereNull('system_key')->latest('updated_at')->get();
        return view('admin.public-site.index', compact('keys', 'systemPages', 'customPages'));
    }

    public function preview(Page $page)
    {
        return view('frontend.cms-page', ['page' => $page, 'preview' => true]);
    }
}
