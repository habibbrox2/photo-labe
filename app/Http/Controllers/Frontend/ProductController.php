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

        $seoData = $seo->getProductMeta($product);

        return view('frontend.products.show', compact('product', 'relatedProducts', 'seoData'));
    }
}
