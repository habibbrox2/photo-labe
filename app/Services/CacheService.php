<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\Service;
use App\Models\PortfolioProject;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\BeforeAfterProject;

class CacheService
{
    protected static int $defaultTTL = 3600; // 1 hour

    /**
     * Get all site settings as a cached collection
     */
    public static function settings(): array
    {
        return Cache::remember('site_settings', self::$defaultTTL, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Get a single setting value
     */
    public static function setting(string $key, mixed $default = null): mixed
    {
        $settings = self::settings();
        return $settings[$key] ?? $default;
    }

    /**
     * Get cached services list
     */
    public static function services()
    {
        return Cache::remember('services_active', self::$defaultTTL, function () {
            return Service::where('is_active', true)
                ->with('category')
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Get cached service by slug
     */
    public static function serviceBySlug(string $slug)
    {
        return Cache::remember("service_{$slug}", self::$defaultTTL, function () use ($slug) {
            return Service::where('slug', $slug)
                ->with(['category', 'features', 'pricings'])
                ->firstOrFail();
        });
    }

    /**
     * Get cached portfolio projects
     */
    public static function portfolioProjects()
    {
        return Cache::remember('portfolio_active', self::$defaultTTL, function () {
            return PortfolioProject::where('is_active', true)
                ->with(['category', 'tags'])
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->get();
        });
    }

    /**
     * Get cached featured products
     */
    public static function featuredProducts()
    {
        return Cache::remember('featured_products', self::$defaultTTL, function () {
            return Product::where('is_active', true)
                ->where('is_featured', true)
                ->with('category')
                ->get();
        });
    }

    /**
     * Get cached testimonials
     */
    public static function testimonials()
    {
        return Cache::remember('testimonials_active', self::$defaultTTL, function () {
            return Testimonial::where('is_active', true)
                ->orderByDesc('sort_order')
                ->get();
        });
    }

    /**
     * Get cached before/after items
     */
    public static function beforeAfterItems()
    {
        return Cache::remember('before_after_active', self::$defaultTTL, function () {
            return BeforeAfterProject::query()
                ->active()
                ->ordered()
                ->get();
        });
    }

    /**
     * Flush all application caches
     */
    public static function flushAll(): void
    {
        $keys = [
            'site_settings',
            'services_active',
            'portfolio_active',
            'featured_products',
            'testimonials_active',
            'before_after_active',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Warm up all caches
     */
    public static function warmUp(): void
    {
        self::settings();
        self::services();
        self::portfolioProjects();
        self::featuredProducts();
        self::testimonials();
        self::beforeAfterItems();
    }
}
