<?php

namespace Database\Seeders;

use App\Models\BeforeAfterCategory;
use App\Models\BeforeAfterProject;
use Illuminate\Database\Seeder;

class BeforeAfterSeeder extends Seeder
{
    public function run(): void
    {
        BeforeAfterCategory::create(['name' => 'Retouching', 'slug' => 'retouching']);
        BeforeAfterCategory::create(['name' => 'Background', 'slug' => 'background']);
        BeforeAfterCategory::create(['name' => 'Color', 'slug' => 'color']);

        $projects = [
            ['category_id' => 1, 'title' => 'Portrait Retouching', 'description' => 'Professional skin retouching and beauty editing', 'is_featured' => true, 'status' => 'published', 'before_image' => 'demo/before_after/portrait-retouching-before.jpg', 'after_image' => 'demo/before_after/portrait-retouching-after.jpg'],
            ['category_id' => 2, 'title' => 'Product Background Removal', 'description' => 'Clean background removal for e-commerce', 'is_featured' => true, 'status' => 'published', 'before_image' => 'demo/before_after/product-background-before.jpg', 'after_image' => 'demo/before_after/product-background-after.jpg'],
            ['category_id' => 3, 'title' => 'Color Grading', 'description' => 'Professional color correction and grading', 'is_featured' => true, 'status' => 'published', 'before_image' => 'demo/before_after/color-grading-before.jpg', 'after_image' => 'demo/before_after/color-grading-after.jpg'],
            ['category_id' => 1, 'title' => 'Beauty Retouching', 'description' => 'High-end beauty retouching', 'is_featured' => true, 'status' => 'published', 'before_image' => 'demo/before_after/beauty-retouching-before.jpg', 'after_image' => 'demo/before_after/beauty-retouching-after.jpg'],
        ];

        foreach ($projects as $project) {
            BeforeAfterProject::create($project);
        }
    }
}
