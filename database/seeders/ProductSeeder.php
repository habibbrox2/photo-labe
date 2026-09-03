<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Lightroom Presets', 'slug' => 'lightroom-presets', 'description' => 'Professional Lightroom presets for photographers'],
            ['name' => 'Photoshop Actions', 'slug' => 'photoshop-actions', 'description' => 'Time-saving Photoshop actions'],
            ['name' => 'Photoshop Brushes', 'slug' => 'photoshop-brushes', 'description' => 'Custom Photoshop brushes'],
            ['name' => 'Overlays', 'slug' => 'overlays', 'description' => 'Photo overlays and effects'],
            ['name' => 'LUTs', 'slug' => 'luts', 'description' => 'Color grading LUTs'],
            ['name' => 'Textures', 'slug' => 'textures', 'description' => 'Background textures and patterns'],
            ['name' => 'Mockups', 'slug' => 'mockups', 'description' => 'Product and branding mockups'],
            ['name' => 'Templates', 'slug' => 'templates', 'description' => 'Design templates for social media and print'],
        ];

        foreach ($categories as $cat) {
            ProductCategory::create($cat);
        }

        $products = [
            ['category_id' => 1, 'title' => 'Cinematic Film Presets', 'slug' => 'cinematic-film-presets', 'price' => 29.99, 'short_description' => '30 cinematic film-style presets for Lightroom', 'description' => '<p>Transform your photos with these 30 cinematic film-inspired presets. Perfect for portrait and street photography.</p>', 'compatibility' => 'Lightroom Classic, Lightroom CC, Camera Raw', 'features' => ['30 presets', 'Before/after preview', 'Works on mobile & desktop'], 'is_featured' => true, 'status' => 'published', 'featured_image' => 'demo/products/cinematic-film-presets.jpg'],
            ['category_id' => 1, 'title' => 'Portrait Perfection Presets', 'slug' => 'portrait-perfection-presets', 'price' => 24.99, 'short_description' => '25 presets designed for portrait photography', 'description' => '<p>Enhance your portraits with these professional presets designed specifically for skin tones and natural lighting.</p>', 'compatibility' => 'Lightroom Classic, Lightroom CC', 'features' => ['25 presets', 'Skin tone optimized', 'One-click apply'], 'is_featured' => true, 'status' => 'published', 'featured_image' => 'demo/products/portrait-perfection-presets.jpg'],
            ['category_id' => 2, 'title' => 'Skin Retouching Actions', 'slug' => 'skin-retouching-actions', 'price' => 19.99, 'short_description' => 'Professional skin retouching in one click', 'description' => '<p>Automate your skin retouching workflow with these professional Photoshop actions.</p>', 'compatibility' => 'Photoshop CC 2020+', 'features' => ['15 actions', 'Frequency separation', 'Dodge & burn'], 'is_featured' => true, 'status' => 'published', 'featured_image' => 'demo/products/skin-retouching-actions.jpg'],
            ['category_id' => 5, 'title' => 'Cinematic LUTs Pack', 'slug' => 'cinematic-luts-pack', 'price' => 34.99, 'short_description' => '20 cinematic LUTs for video color grading', 'description' => '<p>Professional cinematic LUTs for video color grading. Compatible with all major video editors.</p>', 'compatibility' => 'Premiere Pro, DaVinci Resolve, Final Cut Pro', 'features' => ['20 LUTs', '.cube format', '4K compatible'], 'is_featured' => true, 'status' => 'published', 'featured_image' => 'demo/products/cinematic-luts-pack.jpg'],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
