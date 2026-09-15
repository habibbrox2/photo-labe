<?php

namespace Tests\Feature;

use App\Models\BeforeAfterProject;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Support\PreviewGallery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Js;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class AdminListImagePreviewTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    /** Admin lists that preview images: route name plus the record factory key. */
    public static function lists(): array
    {
        return [
            'services' => ['admin.services.index', 'service'],
            'portfolio' => ['admin.portfolio.index', 'portfolio'],
            'products' => ['admin.products.index', 'product'],
        ];
    }

    #[DataProvider('lists')]
    public function test_list_shows_a_thumbnail_that_opens_the_full_image(string $route, string $kind): void
    {
        $record = $this->makeRecord($kind, 'demo/'.$kind.'-hero.jpg');

        $this->actingAs($this->makeAdmin())->get(route($route))
            ->assertOk()
            // The column itself
            ->assertSee('>Image</th>', false)
            // The thumbnail points at the stored image and owns gallery slot 0
            ->assertSee('storage/demo/'.$kind.'-hero.jpg', false)
            ->assertSee('x-on:click="openLightbox(0)"', false)
            ->assertSee('cursor-zoom-in', false)
            // ...and the full-size viewer is wired to the shared Alpine gallery
            ->assertSee('portfolioGallery('.Js::from([asset('storage/demo/'.$kind.'-hero.jpg')]).')', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-label="Close viewer"', false)
            ->assertSee('prevImage()', false)
            ->assertSee('nextImage()', false);
    }

    #[DataProvider('lists')]
    public function test_list_copes_with_a_record_that_has_no_image(string $route, string $kind): void
    {
        $this->makeRecord($kind);

        $this->actingAs($this->makeAdmin())->get(route($route))
            ->assertOk()
            ->assertSee('>Image</th>', false)
            // Nothing to click, and no empty slot for the viewer to page onto.
            ->assertDontSee('openLightbox(0)', false)
            ->assertSee('portfolioGallery('.Js::from([]).')', false);
    }

    public function test_thumbnails_gallery_every_image_on_the_page_in_row_order(): void
    {
        // Three services, but only the first and third have images.
        $first = $this->makeRecord('service', 'demo/services/first.jpg');
        $this->makeRecord('service');
        $third = $this->makeRecord('service', 'demo/services/third.jpg');

        [$urls, $index] = PreviewGallery::for(
            Service::orderBy('id')->get(),
            fn ($service) => ['image' => $service->featured_image]
        );

        $expected = [asset('storage/demo/services/first.jpg'), asset('storage/demo/services/third.jpg')];

        $this->assertSame($expected, $urls);
        // The third service is the *second* image in the flat list, not the third:
        // an imageless row must not leave a dead slot to page onto.
        $this->assertSame(['image' => 0], $index[$first->id]);
        $this->assertSame(['image' => 1], $index[$third->id]);

        $this->actingAs($this->makeAdmin())->get(route('admin.services.index'))
            ->assertOk()
            ->assertSee('x-on:click="openLightbox(0)"', false)
            ->assertSee('x-on:click="openLightbox(1)"', false)
            ->assertDontSee('openLightbox(2)', false)
            ->assertSee('portfolioGallery('.Js::from($expected).')', false);
    }

    public function test_before_after_list_previews_both_images_of_every_row(): void
    {
        $project = BeforeAfterProject::create([
            'title' => 'Retouched portrait',
            'before_image' => 'demo/before.jpg',
            'after_image' => 'demo/after.jpg',
            'status' => 'published',
        ]);

        $expected = [asset('storage/demo/before.jpg'), asset('storage/demo/after.jpg')];

        [$urls, $index] = PreviewGallery::for(collect([$project]), fn ($item) => [
            'before' => $item->before_image,
            'after' => $item->after_image,
        ]);

        $this->assertSame($expected, $urls);
        $this->assertSame(['before' => 0, 'after' => 1], $index[$project->id]);

        $this->actingAs($this->makeAdmin())->get(route('admin.before-after.index'))
            ->assertOk()
            ->assertSee('>Before / After</th>', false)
            ->assertSee('>Before</span>', false)
            ->assertSee('>After</span>', false)
            ->assertSee('x-on:click="openLightbox(0)"', false)
            ->assertSee('x-on:click="openLightbox(1)"', false)
            ->assertSee('portfolioGallery('.Js::from($expected).')', false);
    }

    public function test_before_after_row_without_images_does_not_break_the_row(): void
    {
        // The schema requires both images, but a legacy row could still hold empty
        // strings — the pair must degrade to placeholders instead of a broken slot.
        $project = BeforeAfterProject::create([
            'title' => 'Legacy row',
            'before_image' => '',
            'after_image' => '',
            'status' => 'draft',
        ]);

        [$urls, $index] = PreviewGallery::for(collect([$project]), fn ($item) => [
            'before' => $item->before_image,
            'after' => $item->after_image,
        ]);

        $this->assertSame([], $urls);
        $this->assertSame([], $index);

        $this->actingAs($this->makeAdmin())->get(route('admin.before-after.index'))
            ->assertOk()
            ->assertDontSee('openLightbox(0)', false)
            ->assertSee('portfolioGallery('.Js::from([]).')', false);
    }

    public function test_preview_gallery_skips_records_without_any_image(): void
    {
        $withoutImage = $this->makeRecord('service');
        $withImage = $this->makeRecord('service', 'demo/services/only.jpg');

        [$urls, $index] = PreviewGallery::for(
            Service::orderBy('id')->get(),
            fn ($service) => ['image' => $service->featured_image]
        );

        $this->assertSame([asset('storage/demo/services/only.jpg')], $urls);
        $this->assertSame([$withImage->id => ['image' => 0]], $index);
        $this->assertArrayNotHasKey($withoutImage->id, $index);
    }

    private function makeRecord(string $kind, ?string $image = null): Service|PortfolioProject|\App\Models\Product
    {
        $title = $kind.' '.str()->random(6);

        return match ($kind) {
            'service' => $this->makeService([
                'title' => $title,
                'featured_image' => $image,
            ]),
            'product' => $this->makeProduct([
                'title' => $title,
                'featured_image' => $image,
            ]),
            'portfolio' => PortfolioProject::create([
                'category_id' => PortfolioCategory::firstOrCreate(
                    ['slug' => 'editing'],
                    ['name' => 'Editing', 'is_active' => true]
                )->id,
                'title' => $title,
                'featured_image' => $image,
                'status' => 'published',
            ]),
        };
    }
}
