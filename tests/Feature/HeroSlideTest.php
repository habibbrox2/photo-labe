<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class HeroSlideTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    public function test_guest_cannot_access_hero_slide_admin(): void
    {
        $this->get(route('admin.hero-slides.index'))
            ->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_hero_slide_admin(): void
    {
        $this->actingAs($this->makeCustomer())
            ->get(route('admin.hero-slides.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_a_hero_slide(): void
    {
        Storage::fake('public');

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.hero-slides.store'), [
                'image' => UploadedFile::fake()->image('slide.jpg', 1600, 900),
                'headline' => 'Custom headline',
                'caption_label' => 'Jewelry',
                'caption_text' => 'Diamond Collection',
                'link_url' => '/portfolio/diamonds',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.hero-slides.index'));

        $slide = HeroSlide::first();
        $this->assertSame('Custom headline', $slide->headline);
        $this->assertTrue($slide->is_active);
        Storage::disk('public')->assertExists($slide->image);
    }

    public function test_store_requires_an_image(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.hero-slides.store'), [
                'sort_order' => 0,
            ])
            ->assertSessionHasErrors('image');

        $this->assertSame(0, HeroSlide::count());
    }

    public function test_admin_can_update_a_slide(): void
    {
        $slide = HeroSlide::create([
            'image' => 'demo/portfolio/ecommerce-product-shoot.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.hero-slides.update', $slide), [
                'headline' => 'New year, new visuals.',
                'caption_text' => 'Updated caption',
                'sort_order' => 5,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.hero-slides.index'));

        $slide->refresh();
        $this->assertSame('New year, new visuals.', $slide->headline);
        $this->assertSame(5, $slide->sort_order);
        $this->assertSame('demo/portfolio/ecommerce-product-shoot.jpg', $slide->image);
    }

    public function test_admin_can_reorder_slides(): void
    {
        $a = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);
        $b = HeroSlide::create(['image' => 'b.jpg', 'sort_order' => 2, 'is_active' => true]);

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.hero-slides.up', $b))
            ->assertRedirect();

        $this->assertSame(2, $a->fresh()->sort_order);
        $this->assertSame(1, $b->fresh()->sort_order);
    }

    public function test_admin_can_delete_a_slide(): void
    {
        $slide = HeroSlide::create([
            'image' => 'hero/deleted.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Storage::fake('public');
        Storage::disk('public')->put('hero/deleted.jpg', 'fake');

        $this->actingAs($this->makeAdmin())
            ->delete(route('admin.hero-slides.destroy', $slide))
            ->assertRedirect(route('admin.hero-slides.index'));

        $this->assertDatabaseMissing('hero_slides', ['id' => $slide->id]);
        Storage::disk('public')->assertMissing('hero/deleted.jpg');
    }

    public function test_homepage_uses_managed_slides_with_headline_override(): void
    {
        HeroSlide::create([
            'image' => 'demo/portfolio/summer-fashion-campaign.jpg',
            'headline' => 'Fashion-forward retouching.',
            'caption_label' => 'Fashion',
            'caption_text' => 'Summer Campaign',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('demo/portfolio/summer-fashion-campaign.jpg', $html);
        $this->assertStringContainsString('Fashion-forward retouching.', $html);
        $this->assertStringContainsString('Summer Campaign', $html);
    }

    public function test_inactive_slides_do_not_render(): void
    {
        HeroSlide::create([
            'image' => 'demo/portfolio/ecommerce-product-shoot.jpg',
            'is_active' => false,
        ]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('ecommerce-product-shoot.jpg', $html);
    }

    public function test_homepage_falls_back_to_portfolio_when_no_slides_exist(): void
    {
        $this->makeService();

        $category = \App\Models\PortfolioCategory::create(['name' => 'Product Photography']);

        \App\Models\PortfolioProject::create([
            'category_id' => $category->id,
            'title' => 'Fallback Shoot',
            'slug' => 'fallback-shoot',
            'featured_image' => 'demo/portfolio/ecommerce-product-shoot.jpg',
            'status' => 'published',
            'is_featured' => true,
        ]);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('Fallback Shoot', $html);
    }
}
