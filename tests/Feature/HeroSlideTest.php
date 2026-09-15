<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
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

    public function test_admin_views_render_the_management_ui(): void
    {
        $slide = HeroSlide::create([
            'image' => 'hero/listed.jpg',
            'headline' => 'Listed headline',
            'caption_label' => 'Jewelry',
            'caption_text' => 'Listed caption',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $admin = $this->makeAdmin();

        $this->actingAs($admin)->get(route('admin.hero-slides.index'))
            ->assertOk()
            ->assertSee('storage/hero/listed.jpg', false)
            ->assertSee('Listed headline')
            ->assertSee('Listed caption')
            ->assertSee('Add Slide');

        $this->actingAs($admin)->get(route('admin.hero-slides.create'))
            ->assertOk()
            ->assertSee('name="image"', false)
            ->assertSee('name="headline"', false)
            ->assertSee('name="caption_label"', false)
            ->assertSee('name="caption_text"', false)
            ->assertSee('name="link_url"', false);

        $this->actingAs($admin)->get(route('admin.hero-slides.edit', $slide))
            ->assertOk()
            ->assertSee('Listed headline')
            ->assertSee('name="headline"', false)
            ->assertSee('name="caption_text"', false);
    }

    public function test_edit_page_includes_live_homepage_preview(): void
    {
        $slide = HeroSlide::create([
            'image' => 'hero/preview.jpg',
            'headline' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($this->makeAdmin())
            ->get(route('admin.hero-slides.edit', $slide))
            ->assertOk()
            ->assertSee('data-preview="hero-slide"', false)
            ->assertSee('heroSlidePreview(', false)
            ->assertSee('kenburns', false)
            // Default headline handed to the preview for the empty case
            ->assertSee('refuse to look average', false)
            // Current image wired into the preview
            ->assertSee('storage/hero/preview.jpg', false)
            // The stage is a real 1280px viewport-sized hero, scaled to fit
            ->assertSee('width:1280px', false)
            ->assertSee('transform-origin:top left', false)
            // The copy overlay stays in flow with the homepage's xl metrics, so the
            // stage height is driven by the copy instead of a fixed arbitrary height
            ->assertSee('padding: 10rem 2rem 14rem', false)
            ->assertSee('x-effect="syncHeight()"', false);
    }

    public function test_live_preview_height_follows_the_rendered_copy(): void
    {
        // The stage must not use a hardcoded height: the homepage hero grows with its
        // copy (pt-40/pb-56 plus wrapped text) and the preview has to match it.
        $view = file_get_contents(resource_path('views/admin/hero-slides/edit.blade.php'));

        $this->assertStringContainsString('syncHeight()', $view);
        $this->assertStringContainsString('scaledHeight = Math.round', $view);
        $this->assertStringNotContainsString('height:560px', $view);
    }

    public function test_homepage_default_headline_comes_from_model_constant(): void
    {
        $this->assertStringContainsString('refuse to look average', HeroSlide::DEFAULT_HEADLINE);

        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('refuse to look average', $html);
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

    public function test_admin_can_reorder_slides_by_dragging(): void
    {
        $slides = collect(['a.jpg', 'b.jpg', 'c.jpg'])->map(fn ($image, $index) => HeroSlide::create([
            'image' => $image,
            'sort_order' => $index + 1,
            'is_active' => true,
        ]));

        // Drag the last slide to the top, as the sortable list would send it.
        $order = $slides->pluck('id')->reverse()->values();

        $this->actingAs($this->makeAdmin())
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => $order->all()])
            ->assertOk()
            ->assertJson(['order' => $order->all()]);

        $this->assertSame(
            $order->all(),
            HeroSlide::ordered()->pluck('id')->all()
        );
        $this->assertSame([1, 2, 3], HeroSlide::ordered()->pluck('sort_order')->all());
    }

    public function test_reorder_renumbers_every_slide_from_one(): void
    {
        // Deliberately messy data: gaps and duplicates left behind by pair swapping.
        $a = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 7, 'is_active' => true]);
        $b = HeroSlide::create(['image' => 'b.jpg', 'sort_order' => 7, 'is_active' => true]);
        $c = HeroSlide::create(['image' => 'c.jpg', 'sort_order' => 40, 'is_active' => true]);

        $this->actingAs($this->makeAdmin())
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => [$b->id, $c->id, $a->id]])
            ->assertOk();

        $this->assertSame(1, $b->fresh()->sort_order);
        $this->assertSame(2, $c->fresh()->sort_order);
        $this->assertSame(3, $a->fresh()->sort_order);
    }

    public function test_reorder_ignores_slides_that_no_longer_exist(): void
    {
        $a = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);
        $b = HeroSlide::create(['image' => 'b.jpg', 'sort_order' => 2, 'is_active' => true]);

        // A stale tab sends an id that has since been deleted.
        $this->actingAs($this->makeAdmin())
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => [$b->id, 99999, $a->id]])
            ->assertOk();

        $this->assertSame([$b->id, $a->id], HeroSlide::ordered()->pluck('id')->all());
        $this->assertSame([1, 2], HeroSlide::ordered()->pluck('sort_order')->all());
    }

    public function test_reorder_leaves_unmentioned_slides_after_the_reordered_ones(): void
    {
        $a = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);
        $b = HeroSlide::create(['image' => 'b.jpg', 'sort_order' => 2, 'is_active' => true]);
        $c = HeroSlide::create(['image' => 'c.jpg', 'sort_order' => 3, 'is_active' => true]);

        // Only the first two are dragged, so `c` must not collide with them.
        $this->actingAs($this->makeAdmin())
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => [$b->id, $a->id]])
            ->assertOk();

        $this->assertSame([$b->id, $a->id, $c->id], HeroSlide::ordered()->pluck('id')->all());
        $this->assertSame([1, 2, 3], HeroSlide::ordered()->pluck('sort_order')->all());
    }

    public function test_reorder_flushes_the_homepage_hero_cache(): void
    {
        $a = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);
        $b = HeroSlide::create(['image' => 'b.jpg', 'sort_order' => 2, 'is_active' => true]);

        Cache::spy();

        $this->actingAs($this->makeAdmin())
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => [$b->id, $a->id]])
            ->assertOk();

        Cache::shouldHaveReceived('forget')->once()->with('home_hero_slides');
    }

    public function test_reorder_validates_the_payload(): void
    {
        $slide = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->patchJson(route('admin.hero-slides.reorder'), [])
            ->assertJsonValidationErrors('order');

        $this->actingAs($admin)
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => [$slide->id, $slide->id]])
            ->assertJsonValidationErrors('order.1');

        $this->actingAs($admin)
            ->patchJson(route('admin.hero-slides.reorder'), ['order' => ['not-an-id']])
            ->assertJsonValidationErrors('order.0');
    }

    public function test_reorder_requires_an_admin(): void
    {
        $slide = HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);

        $this->patch(route('admin.hero-slides.reorder'), ['order' => [$slide->id]])
            ->assertRedirect(route('login'));

        $this->actingAs($this->makeCustomer())
            ->patch(route('admin.hero-slides.reorder'), ['order' => [$slide->id]])
            ->assertForbidden();
    }

    public function test_index_uses_a_drag_handle_instead_of_up_down_buttons(): void
    {
        HeroSlide::create(['image' => 'a.jpg', 'sort_order' => 1, 'is_active' => true]);
        HeroSlide::create(['image' => 'b.jpg', 'sort_order' => 2, 'is_active' => true]);

        $response = $this->actingAs($this->makeAdmin())->get(route('admin.hero-slides.index'));

        $response->assertOk()
            ->assertSee('heroSlideSortable(', false)
            ->assertSee('draggable="true"', false)
            ->assertSee('data-slide-id', false)
            ->assertSee(route('admin.hero-slides.reorder'), false)
            ->assertSee('x-on:keydown.arrow-up.prevent="moveBy($event, -1)"', false)
            // The up/down posting buttons are gone for good.
            ->assertDontSee('Move up')
            ->assertDontSee('Move down');
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
