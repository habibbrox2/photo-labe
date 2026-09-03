<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\SeoService;

class ProductController extends Controller
{
    public function index(SeoService $seo)
    {
        $categories = ProductCategory::active()->ordered()->withCount('products')->get();
        $products = Product::active()->ordered()->with('category', 'images')->paginate(12);

        $seoData = $seo->getMeta([
            'title' => 'Digital Products - Presets, Actions & Templates',
            'description' => 'Professional Lightroom presets, Photoshop actions, brushes, overlays, and design templates for photographers and designers.',
            'keywords' => [
                'digital products',
                'lightroom presets',
                'photoshop actions',
                'photo editing tools',
                'presets for lightroom',
                'photoshop brushes',
                'photo overlays',
                'LUTs',
                'textures',
                'mockups',
            ],
            'breadcrumb' => [
                ['name' => 'Home', 'url' => '/'],
                ['name' => 'Products', 'url' => '/products'],
            ],
        ]);

        return view('frontend.products.index', compact('categories', 'products', 'seoData'));
    }

    public function show(string $slug, SeoService $seo)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with('category', 'images', 'files')
            ->firstOrFail();

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->limit(4)
            ->get();

        $beforeAfterItems = \App\Models\BeforeAfterProject::active()
            ->featured()
            ->ordered()
            ->limit(3)
            ->get();

        // Hero demo: pick the before/after pair that best matches this product.
        $heroBeforeAfter = null;
        $demoPool = \App\Models\BeforeAfterProject::active()
            ->ordered()
            ->with('category')
            ->get();
        if ($demoPool->isNotEmpty()) {
            $needle = strtolower(
                trim(($product->title ?? '').' '.($product->category->name ?? '').' '.($product->short_description ?? ''))
            );
            $matched = $demoPool->filter(function ($project) use ($needle) {
                $category = strtolower((string) ($project->category->name ?? ''));

                if (str_contains($category, 'retouch') && preg_match('/portrait|skin|beauty|retouch|people|model/', $needle)) {
                    return true;
                }
                if (str_contains($category, 'color') && preg_match('/color|cinematic|film|lut|preset|grade|tone/', $needle)) {
                    return true;
                }
                if (str_contains($category, 'background') && preg_match('/background|remove|overlay|ecommerce/', $needle)) {
                    return true;
                }

                return false;
            });
            $heroBeforeAfter = $matched->first() ?? $demoPool->first();
        }

        $seoData = $seo->getProductMeta($product);

        return view('frontend.products.show', compact('product', 'relatedProducts', 'beforeAfterItems', 'heroBeforeAfter', 'seoData'));
    }
}
