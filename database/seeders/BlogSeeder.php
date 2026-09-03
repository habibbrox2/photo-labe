<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Photography Tips', 'slug' => 'photography-tips'],
            ['name' => 'Photo Editing', 'slug' => 'photo-editing'],
            ['name' => 'Industry News', 'slug' => 'industry-news'],
            ['name' => 'Tutorials', 'slug' => 'tutorials'],
        ];

        foreach ($categories as $cat) {
            BlogCategory::create($cat);
        }

        $tags = ['Lightroom', 'Photoshop', 'Retouching', 'Color Correction', 'Product Photography', 'Portrait'];
        foreach ($tags as $tagName) {
            BlogTag::create(['name' => $tagName, 'slug' => \Illuminate\Support\Str::slug($tagName)]);
        }

        $admin = User::where('email', 'admin@photolabe.com')->first();

        $posts = [
            ['category_id' => 1, 'author_id' => $admin?->id, 'title' => '10 Product Photography Tips for E-commerce', 'slug' => '10-product-photography-tips', 'excerpt' => 'Master product photography with these essential tips for e-commerce success.', 'content' => '<p>Product photography is crucial for e-commerce success. Here are 10 tips to help you capture stunning product images that convert.</p><h2>1. Use Proper Lighting</h2><p>Good lighting is the foundation of great product photography. Natural light or studio strobes can make a huge difference.</p>', 'status' => 'published', 'published_at' => now()->subDays(5), 'is_featured' => true, 'featured_image' => 'demo/blog/product-photography-tips.jpg'],
            ['category_id' => 2, 'author_id' => $admin?->id, 'title' => 'Complete Guide to Background Removal', 'slug' => 'complete-guide-background-removal', 'excerpt' => 'Learn professional techniques for perfect background removal in Photoshop.', 'content' => '<p>Background removal is one of the most requested photo editing services. Here is our complete guide to achieving perfect results.</p>', 'status' => 'published', 'published_at' => now()->subDays(3), 'is_featured' => true, 'featured_image' => 'demo/blog/background-removal-guide.jpg'],
            ['category_id' => 4, 'author_id' => $admin?->id, 'title' => 'Color Correction Mastery: A Step-by-Step Tutorial', 'slug' => 'color-correction-mastery', 'excerpt' => 'Master color correction with this comprehensive step-by-step tutorial.', 'content' => '<p>Color correction is an essential skill for any photographer or retoucher. This tutorial covers everything from white balance to creative color grading.</p>', 'status' => 'published', 'published_at' => now()->subDay(), 'is_featured' => true, 'featured_image' => 'demo/blog/color-correction-tutorial.jpg'],
        ];

        foreach ($posts as $postData) {
            BlogPost::create($postData);
        }
    }
}
