<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\SeoService;

class ServiceController extends Controller
{
    public function index(SeoService $seo)
    {
        $categories = ServiceCategory::active()->ordered()->withCount('services')->get();
        $services = Service::active()->ordered()->with('category')->paginate(12);

        $seoData = $seo->getMeta([
            'title' => 'Our Services',
            'description' => 'Professional photo editing services including retouching, background removal, color correction, clipping path, and creative design.',
            'keywords' => [
                'photo editing services',
                'photo retouching',
                'background removal',
                'color correction',
                'clipping path',
                'jewelry retouching',
                'product photo editing',
                'creative design services',
            ],
            'breadcrumb' => [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Services', 'url' => '/services'],
            ],
        ]);

        return view('frontend.services.index', compact('categories', 'services', 'seoData'));
    }

    public function show(string $slug, SeoService $seo)
    {
        $service = Service::where('slug', $slug)
            ->active()
            ->with('category', 'features', 'pricing')
            ->firstOrFail();

        $relatedServices = Service::active()
            ->where('category_id', $service->category_id)
            ->where('id', '!=', $service->id)
            ->with('category')
            ->limit(3)
            ->get();

        $seoData = $seo->getServiceMeta($service);

        return view('frontend.services.show', compact('service', 'relatedServices', 'seoData'));
    }
}
