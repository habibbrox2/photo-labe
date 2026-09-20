<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_index_renders_with_filter_bar(): void
    {
        $category = ProductCategory::factory()->create(['name' => 'Presets']);
        Product::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee('Search presets', false)
            ->assertSee('All categories');
    }

    public function test_products_can_be_filtered_by_search_and_category(): void
    {
        $presets = ProductCategory::factory()->create(['name' => 'Presets', 'slug' => 'presets']);
        $actions = ProductCategory::factory()->create(['name' => 'Actions', 'slug' => 'actions']);

        Product::factory()->create(['title' => 'Cinematic Film Presets', 'category_id' => $presets->id]);
        Product::factory()->create(['title' => 'Skin Retouching Actions', 'category_id' => $actions->id]);

        $this->get('/products?q=cinematic')
            ->assertOk()
            ->assertSee('Cinematic Film Presets')
            ->assertDontSee('Skin Retouching Actions');

        $this->get('/products?category=actions')
            ->assertOk()
            ->assertSee('Skin Retouching Actions')
            ->assertDontSee('Cinematic Film Presets');
    }

    public function test_products_can_be_sorted_by_price(): void
    {
        Product::factory()->create(['title' => 'Cheap Pack', 'price' => 5]);
        Product::factory()->create(['title' => 'Expensive Pack', 'price' => 50]);

        $response = $this->get('/products?sort=price_low');

        $response->assertOk();
        $this->assertTrue(
            str_contains($response->getContent(), 'Cheap Pack')
            && strpos($response->getContent(), 'Cheap Pack') < strpos($response->getContent(), 'Expensive Pack')
        );
    }

    public function test_empty_search_shows_no_results_state(): void
    {
        Product::factory()->create(['title' => 'Some Pack']);

        $this->get('/products?q=nonexistent')
            ->assertOk()
            ->assertSee('No products found');
    }
}
