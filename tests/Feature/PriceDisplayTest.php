<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class PriceDisplayTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    /*
     * ── helpers ──
     */

    private function setCurrency(string $code): void
    {
        Setting::set('currency', $code);
    }

    /*
     * ── currency display ──
     */

    public function test_public_prices_render_in_the_configured_currency(): void
    {
        $this->setCurrency('USD');
        $this->makeService(['starting_price' => 4.99, 'is_featured' => true]);

        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee('$4.99')
            ->assertDontSee('৳4.99');
    }

    public function test_switching_currency_updates_public_prices(): void
    {
        $this->setCurrency('BDT');
        $this->makeService(['starting_price' => 4.99, 'is_featured' => true]);

        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee('৳4.99')
            ->assertDontSee('$4.99');
    }

    public function test_product_prices_render_in_the_configured_currency(): void
    {
        $this->setCurrency('BDT');
        $product = $this->makeProduct(['price' => 29.99, 'is_featured' => true]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('৳29.99');
    }

    public function test_seo_schema_uses_the_configured_currency(): void
    {
        $this->setCurrency('BDT');

        $schema = app(SeoService::class)->getServiceMeta(
            $this->makeService(['starting_price' => 4.99])
        )['schema'];

        $this->assertSame('BDT', $schema['offers']['priceCurrency']);
    }

    /*
     * ── sale price handling ──
     */

    public function test_lower_sale_price_is_shown_with_strikethrough_regular_price(): void
    {
        $product = $this->makeProduct([
            'price' => 29.99,
            'sale_price' => 19.99,
            'is_featured' => true,
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('19.99')
            ->assertSee('29.99')
            ->assertSee('Sale');
    }

    public function test_sale_price_above_regular_price_is_ignored(): void
    {
        $product = $this->makeProduct([
            'price' => 29.99,
            'sale_price' => 34.99, // admin typo — must not raise the shown price
            'is_featured' => true,
        ]);

        $page = $this->get(route('products.show', $product->slug))->assertOk();

        $this->assertSame(29.99, $product->effective_price);
        $this->assertFalse($product->on_sale);
        $page->assertDontSee('Sale');
    }

    public function test_sale_price_equal_to_regular_price_is_not_a_sale(): void
    {
        $product = $this->makeProduct([
            'price' => 29.99,
            'sale_price' => 29.99,
            'is_featured' => true,
        ]);

        $this->assertSame(29.99, $product->effective_price);
        $this->assertFalse($product->on_sale);

        $this->get(route('products.show', $product->slug))->assertOk()->assertDontSee('Sale');
    }

    public function test_product_schema_uses_effective_sale_price(): void
    {
        $product = $this->makeProduct(['price' => 29.99, 'sale_price' => 19.99]);

        $schema = app(SeoService::class)->getProductMeta($product)['schema'];

        $this->assertSame('19.99', (string) $schema['offers']['price']);
    }

    public function test_homepage_hides_product_price_but_keeps_sale_logic_intact(): void
    {
        Setting::set('products_show_price', '0');

        $this->makeProduct([
            'price' => 29.99,
            'sale_price' => 19.99,
            'is_featured' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('29.99')
            ->assertSee('Price on request');
    }
}
