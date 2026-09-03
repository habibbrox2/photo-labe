<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CacheService;
use App\Services\SeoService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(SeoService $seo)
    {
        $featuredServices = Cache::remember(
            'home_featured_services', 3600,
            fn () => \App\Models\Service::active()->featured()->with('category')->limit(6)->get()
        );

        $featuredPortfolio = Cache::remember(
            'home_featured_portfolio', 3600,
            fn () => \App\Models\PortfolioProject::active()->featured()->with('category')->limit(6)->get()
        );

        $beforeAfter = Cache::remember(
            'home_before_after', 3600,
            fn () => \App\Models\BeforeAfterProject::active()->featured()->limit(8)->get()
        );

        $featuredProducts = Cache::remember(
            'home_featured_products', 3600,
            fn () => \App\Models\Product::active()->featured()->with('category')->limit(4)->get()
        );

        $latestPosts = Cache::remember(
            'home_latest_posts', 1800,
            fn () => \App\Models\BlogPost::active()->published()->recent()->with('category', 'author')->limit(3)->get()
        );

        $testimonials = Cache::remember(
            'home_testimonials', 3600,
            fn () => \App\Models\Testimonial::active()->featured()->limit(5)->get()
        );

        // SEO data
        $seoData = $seo->getMeta([
            'title' => 'Professional Photo Editing & Creative Design Services',
            'description' => 'Transform your images into professional, market-ready visuals with expert photo editing, background removal, color correction, and creative design services.',
            'keywords' => [
                'photo editing',
                'photo retouching',
                'background removal',
                'color correction',
                'clipping path',
                'jewelry retouching',
                'product photography',
                'creative design',
                'professional photo editing',
                'image editing services',
                'e-commerce photo editing',
                'portrait retouching',
            ],
            'schema' => $seo->getHomepageSchema(),
        ]);

        return view('frontend.home', compact(
            'featuredServices',
            'featuredPortfolio',
            'beforeAfter',
            'featuredProducts',
            'latestPosts',
            'testimonials',
            'seoData'
        ));
    }
}
