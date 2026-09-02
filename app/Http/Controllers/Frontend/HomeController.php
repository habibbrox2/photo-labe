<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Use cached queries with tags for easy invalidation
        $featuredServices = Cache::tags(['home', 'services'])->remember(
            'home_featured_services', 3600,
            fn () => \App\Models\Service::active()->featured()->with('category')->limit(6)->get()
        );

        $featuredPortfolio = Cache::tags(['home', 'portfolio'])->remember(
            'home_featured_portfolio', 3600,
            fn () => \App\Models\PortfolioProject::active()->featured()->with('category')->limit(6)->get()
        );

        $beforeAfter = Cache::tags(['home', 'before_after'])->remember(
            'home_before_after', 3600,
            fn () => \App\Models\BeforeAfterProject::active()->featured()->limit(8)->get()
        );

        $featuredProducts = Cache::tags(['home', 'products'])->remember(
            'home_featured_products', 3600,
            fn () => \App\Models\Product::active()->featured()->with('category')->limit(4)->get()
        );

        $latestPosts = Cache::tags(['home', 'blog'])->remember(
            'home_latest_posts', 1800,
            fn () => \App\Models\BlogPost::active()->published()->recent()->with('category', 'author')->limit(3)->get()
        );

        $testimonials = Cache::tags(['home', 'testimonials'])->remember(
            'home_testimonials', 3600,
            fn () => \App\Models\Testimonial::active()->featured()->limit(5)->get()
        );

        return view('frontend.home', compact(
            'featuredServices',
            'featuredPortfolio',
            'beforeAfter',
            'featuredProducts',
            'latestPosts',
            'testimonials'
        ));
    }
}
