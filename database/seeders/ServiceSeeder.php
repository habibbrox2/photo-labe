<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServiceFeature;
use App\Models\ServicePricing;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Photo Retouching', 'slug' => 'photo-retouching', 'description' => 'Professional photo retouching services'],
            ['name' => 'Background Removal', 'slug' => 'background-removal', 'description' => 'Clean background removal and replacement'],
            ['name' => 'Color Correction', 'slug' => 'color-correction', 'description' => 'Professional color grading and correction'],
            ['name' => 'Creative Design', 'slug' => 'creative-design', 'description' => 'Graphic design and creative services'],
        ];

        foreach ($categories as $cat) {
            ServiceCategory::create($cat);
        }

        $services = [
            [
                'category_id' => 1, 'title' => 'Professional Photo Retouching',
                'slug' => 'professional-photo-retouching',
                'short_description' => 'Expert retouching for portraits, products, and commercial photography.',
                'description' => '<p>Our professional photo retouching service covers everything from basic cleanup to advanced skin retouching, object removal, and compositing.</p><p>We work with photographers, e-commerce businesses, and brands to deliver pixel-perfect results.</p>',
                'starting_price' => 2.99, 'delivery_time' => '24 hours', 'is_featured' => true, 'status' => 'published',
                'featured_image' => 'demo/services/photo-retouching.jpg',
            ],
            [
                'category_id' => 2, 'title' => 'Background Removal',
                'slug' => 'background-removal',
                'short_description' => 'Precise background removal with clean edges for product photography.',
                'description' => '<p>Get clean, professional product images with perfectly removed backgrounds. Our clipping path experts deliver pixel-perfect results.</p>',
                'starting_price' => 1.49, 'delivery_time' => '12 hours', 'is_featured' => true, 'status' => 'published',
                'featured_image' => 'demo/services/background-removal.jpg',
            ],
            [
                'category_id' => 3, 'title' => 'Color Correction',
                'slug' => 'color-correction',
                'short_description' => 'Professional color grading, white balance, and exposure correction.',
                'description' => '<p>Transform your images with professional color correction. We fix white balance, exposure, contrast, and color grading.</p>',
                'starting_price' => 1.99, 'delivery_time' => '12 hours', 'is_featured' => true, 'status' => 'published',
                'featured_image' => 'demo/services/color-correction.jpg',
            ],
            [
                'category_id' => 4, 'title' => 'Graphic Design',
                'slug' => 'graphic-design',
                'short_description' => 'Creative graphic design for marketing materials and branding.',
                'description' => '<p>From social media graphics to print materials, our design team creates stunning visuals that capture attention.</p>',
                'starting_price' => 29.99, 'delivery_time' => '48 hours', 'is_featured' => true, 'status' => 'published',
                'featured_image' => 'demo/services/graphic-design.jpg',
            ],
            [
                'category_id' => 1, 'title' => 'Jewelry Retouching',
                'slug' => 'jewelry-retouching',
                'short_description' => 'Specialized retouching for jewelry photography.',
                'description' => '<p>Expert jewelry retouching including stone enhancement, metal polishing, shadow creation, and background cleanup.</p>',
                'starting_price' => 3.99, 'delivery_time' => '24 hours', 'is_featured' => true, 'status' => 'published',
                'featured_image' => 'demo/services/jewelry-retouching.jpg',
            ],
            [
                'category_id' => 2, 'title' => 'Clipping Path',
                'slug' => 'clipping-path',
                'short_description' => 'Precise manual clipping paths for complex objects.',
                'description' => '<p>Hand-drawn clipping paths for products with complex shapes like hair, fur, and transparent objects.</p>',
                'starting_price' => 1.99, 'delivery_time' => '12 hours', 'is_featured' => true, 'status' => 'published',
                'featured_image' => 'demo/services/clipping-path.jpg',
            ],
        ];

        foreach ($services as $serviceData) {
            $service = Service::create($serviceData);

            // Add features
            ServiceFeature::create(['service_id' => $service->id, 'title' => 'High Quality', 'description' => 'Pixel-perfect results', 'sort_order' => 1]);
            ServiceFeature::create(['service_id' => $service->id, 'title' => 'Fast Delivery', 'description' => 'Quick turnaround time', 'sort_order' => 2]);
            ServiceFeature::create(['service_id' => $service->id, 'title' => 'Free Revisions', 'description' => 'Until you are satisfied', 'sort_order' => 3]);

            // Add pricing
            ServicePricing::create(['service_id' => $service->id, 'plan_name' => 'Basic', 'price' => $service->starting_price, 'description' => 'Standard quality', 'features' => ['5 images', '24h delivery', '1 revision'], 'sort_order' => 1]);
            ServicePricing::create(['service_id' => $service->id, 'plan_name' => 'Premium', 'price' => $service->starting_price * 2, 'description' => 'Premium quality', 'features' => ['20 images', '12h delivery', 'Unlimited revisions'], 'is_popular' => true, 'sort_order' => 2]);
            ServicePricing::create(['service_id' => $service->id, 'plan_name' => 'Enterprise', 'price' => $service->starting_price * 5, 'description' => 'Bulk pricing', 'features' => ['100+ images', 'Priority support', 'Dedicated designer'], 'sort_order' => 3]);
        }
    }
}
