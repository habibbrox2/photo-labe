<?php

namespace Database\Seeders;

use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\PortfolioTag;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Product Photography', 'slug' => 'product-photography'],
            ['name' => 'Jewelry', 'slug' => 'jewelry'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Real Estate', 'slug' => 'real-estate'],
            ['name' => 'Wedding', 'slug' => 'wedding'],
            ['name' => 'Graphic Design', 'slug' => 'graphic-design'],
        ];

        foreach ($categories as $cat) {
            PortfolioCategory::create($cat);
        }

        $tags = ['Retouching', 'Color Correction', 'Background Removal', 'Composite', 'Shadow', 'Clipping Path'];
        foreach ($tags as $tagName) {
            PortfolioTag::create(['name' => $tagName, 'slug' => \Illuminate\Support\Str::slug($tagName)]);
        }

        $projects = [
            ['category_id' => 1, 'title' => 'E-commerce Product Shoot', 'slug' => 'ecommerce-product-shoot', 'client' => 'FashionCo', 'description' => '<p>Complete product photography and retouching for an online fashion retailer.</p>', 'status' => 'published', 'is_featured' => true],
            ['category_id' => 2, 'title' => 'Luxury Diamond Collection', 'slug' => 'luxury-diamond-collection', 'client' => 'GemHouse', 'description' => '<p>High-end jewelry retouching for a luxury brand catalog.</p>', 'status' => 'published', 'is_featured' => true],
            ['category_id' => 3, 'title' => 'Summer Fashion Campaign', 'slug' => 'summer-fashion-campaign', 'client' => 'StyleBrand', 'description' => '<p>Fashion photography retouching for seasonal marketing campaign.</p>', 'status' => 'published', 'is_featured' => true],
            ['category_id' => 4, 'title' => 'Luxury Apartment Listings', 'slug' => 'luxury-apartment-listings', 'client' => 'PrimeRealty', 'description' => '<p>Real estate photo enhancement for luxury property listings.</p>', 'status' => 'published', 'is_featured' => true],
            ['category_id' => 5, 'title' => 'Destination Wedding Album', 'slug' => 'destination-wedding-album', 'client' => 'Sarah & Mike', 'description' => '<p>Complete wedding photo editing for a destination wedding.</p>', 'status' => 'published', 'is_featured' => true],
            ['category_id' => 6, 'title' => 'Brand Identity Design', 'slug' => 'brand-identity-design', 'client' => 'TechStartup', 'description' => '<p>Complete brand identity and marketing materials design.</p>', 'status' => 'published', 'is_featured' => true],
        ];

        foreach ($projects as $projectData) {
            $project = PortfolioProject::create($projectData);
            $allTags = [1, 2, 3, 4, 5, 6];
            shuffle($allTags);
            $project->tags()->sync(array_slice($allTags, 0, 2));
        }
    }
}
