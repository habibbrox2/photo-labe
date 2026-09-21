<?php

namespace Tests\Feature;

use App\Models\ServicePricing;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class ServicePriceVisibilityTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    /*
     * ── helpers ──
     */

    private function serviceWithPricing(): \App\Models\Service
    {
        return $this->makeService([
            'title' => 'Portrait Retouching',
            'starting_price' => 4.99,
            'short_description' => 'Expert portrait retouching.',
            'status' => 'published',
            'is_featured' => true,
        ]);
    }

    private function setPriceVisibility(bool $on): void
    {
        Setting::set('services_show_price', $on ? '1' : '0');
    }

    private function productWithPrice(): \App\Models\Product
    {
        return $this->makeProduct([
            'title' => 'Lightroom Preset Pack',
            'price' => 29.99,
            'short_description' => 'Professional color presets.',
            'status' => 'published',
            'is_featured' => true,
        ]);
    }

    private function setProductPriceVisibility(bool $on): void
    {
        Setting::set('products_show_price', $on ? '1' : '0');
    }

    /*
     * ── service price visibility tests ──
     */

    public function test_prices_show_by_default_on_public_pages(): void
    {
        $service = $this->serviceWithPricing();
        ServicePricing::create([
            'service_id' => $service->id,
            'plan_name' => 'Basic',
            'price' => 4.99,
        ]);

        $this->get(route('services.index'))->assertOk()->assertSee('4.99');
        $this->get(route('services.show', $service->slug))->assertOk()->assertSee('4.99');
        $this->get('/')->assertOk()->assertSee('4.99');
    }

    public function test_prices_hidden_when_disabled(): void
    {
        $this->setPriceVisibility(false);

        $service = $this->serviceWithPricing();
        ServicePricing::create([
            'service_id' => $service->id,
            'plan_name' => 'Basic',
            'price' => 4.99,
        ]);

        $this->get(route('services.index'))
            ->assertOk()
            ->assertDontSee('4.99')
            ->assertSee('Custom pricing');

        $this->get(route('services.show', $service->slug))
            ->assertOk()
            ->assertDontSee('4.99');

        $this->get('/')
            ->assertOk()
            ->assertDontSee('4.99');
    }

    public function test_prices_hidden_from_quote_form_dropdown(): void
    {
        $this->setPriceVisibility(false);
        $service = $this->serviceWithPricing();

        $this->get(route('quote.create'))
            ->assertOk()
            ->assertDontSee('4.99')
            ->assertSee($service->title, false);
    }

    public function test_price_hidden_from_service_schema_when_disabled(): void
    {
        $this->setPriceVisibility(false);
        $service = $this->serviceWithPricing();

        $schema = app(\App\Services\SeoService::class)->getServiceMeta($service)['schema'];

        $this->assertArrayNotHasKey('offers', $schema);
    }

    public function test_price_still_in_schema_when_enabled(): void
    {
        $service = $this->serviceWithPricing();

        $schema = app(\App\Services\SeoService::class)->getServiceMeta($service)['schema'];

        $this->assertSame('4.99', (string) $schema['offers']['price']);
    }

    public function test_admin_can_toggle_setting_and_it_persists(): void
    {
        $admin = $this->makeAdmin();

        // Default is on.
        $this->assertTrue(Setting::flag('services_show_price', true));

        // Unchecking the box (value absent from payload) turns it off.
        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['settings' => ['site_name' => 'PhotoLabe']])
            ->assertRedirect();

        $this->assertFalse(Setting::flag('services_show_price'));

        // Turning it back on shows prices again.
        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['settings' => ['services_show_price' => '1']])
            ->assertRedirect();

        $this->assertTrue(Setting::flag('services_show_price'));
    }

    public function test_setting_boolean_helper_tolerates_stored_strings(): void
    {
        Setting::set('services_show_price', '0');
        $this->assertFalse(Setting::flag('services_show_price'));

        Setting::set('services_show_price', '1');
        $this->assertTrue(Setting::flag('services_show_price'));

        Setting::where('key', 'services_show_price')->delete();
        $this->assertTrue(Setting::flag('services_show_price', true));
        $this->assertFalse(Setting::flag('services_show_price', false));
    }

    /*
     * ── product price visibility tests ──
     */

    public function test_product_prices_show_by_default_on_public_pages(): void
    {
        $product = $this->productWithPrice();

        $this->get(route('products.index'))->assertOk()->assertSee('29.99');
        $this->get(route('products.show', $product->slug))->assertOk()->assertSee('29.99');
        $this->get('/')->assertOk()->assertSee('29.99');
    }

    public function test_product_prices_hidden_when_disabled(): void
    {
        $this->setProductPriceVisibility(false);
        $product = $this->productWithPrice();

        $this->get(route('products.index'))
            ->assertOk()
            ->assertDontSee('29.99')
            ->assertSee('Price on request');

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertDontSee('29.99')
            ->assertSee('Price on request');

        $this->get('/')
            ->assertOk()
            ->assertDontSee('29.99');
    }

    public function test_product_price_hidden_from_schema_when_disabled(): void
    {
        $this->setProductPriceVisibility(false);
        $product = $this->productWithPrice();

        $schema = app(\App\Services\SeoService::class)->getProductMeta($product)['schema'];

        $this->assertArrayNotHasKey('offers', $schema);
    }

    public function test_product_price_still_in_schema_when_enabled(): void
    {
        $product = $this->productWithPrice();

        $schema = app(\App\Services\SeoService::class)->getProductMeta($product)['schema'];

        $this->assertSame('29.99', (string) $schema['offers']['price']);
    }

    public function test_admin_can_toggle_product_price_setting_and_it_persists(): void
    {
        $admin = $this->makeAdmin();

        // Default is on.
        $this->assertTrue(Setting::flag('products_show_price', true));

        // Unchecking the box (value absent from payload) turns it off.
        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['settings' => ['site_name' => 'PhotoLabe']])
            ->assertRedirect();

        $this->assertFalse(Setting::flag('products_show_price'));

        // Turning it back on shows prices again.
        $this->actingAs($admin)
            ->put(route('admin.settings.update'), ['settings' => ['products_show_price' => '1']])
            ->assertRedirect();

        $this->assertTrue(Setting::flag('products_show_price'));
    }
}
