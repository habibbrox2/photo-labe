<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;

class ServiceController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::active()->ordered()->withCount('services')->get();
        $services = Service::active()->ordered()->with('category')->paginate(12);

        return view('frontend.services.index', compact('categories', 'services'));
    }

    public function show(string $slug)
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

        return view('frontend.services.show', compact('service', 'relatedServices'));
    }
}
