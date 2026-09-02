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
            ['category_id' => 1, 'title' => 'Portrait Retouching', 'description' => 'Professional skin retouching and beauty editing', 'is_featured' => true, 'status' => 'published'],
            ['category_id' => 2, 'title' => 'Product Background Removal', 'description' => 'Clean background removal for e-commerce', 'is_featured' => true, 'status' => 'published'],
            ['category_id' => 3, 'title' => 'Color Grading', 'description' => 'Professional color correction and grading', 'is_featured' => true, 'status' => 'published'],
            ['category_id' => 1, 'title' => 'Beauty Retouching', 'description' => 'High-end beauty retouching', 'is_featured' => true, 'status' => 'published'],
        ];

        foreach ($projects as $project) {
            BeforeAfterProject::create(array_merge($project, [
                'before_image' => 'demo/before.jpg',
                'after_image' => 'demo/after.jpg',
            ]));
        }
    }
}
