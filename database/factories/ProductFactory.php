<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'category_id' => ProductCategory::factory(),
            'title' => ucfirst($title),
            'price' => fake()->randomFloat(2, 5, 50),
            'short_description' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'download_count' => 0,
            'is_featured' => false,
            'status' => 'published',
        ];
    }
}
