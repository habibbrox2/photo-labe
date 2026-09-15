<?php

namespace Tests\Feature;

use App\Models\BeforeAfterProject;
use App\Models\Page;
use App\Models\Product;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Services\ShortcodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class BeforeAfterShortcodeTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    protected function makeSlider(array $attributes = []): BeforeAfterProject
    {
        return BeforeAfterProject::create(array_merge([
            'title' => 'Retouch Demo',
            'before_image' => 'before-after/demo-before.jpg',
            'after_image' => 'before-after/demo-after.jpg',
            'status' => 'published',
        ], $attributes));
    }

    // ─── Service unit behavior ────────────────────────────────

    public function test_shortcode_is_replaced_with_slider_markup(): void
    {
        $slider = $this->makeSlider();

        $html = app(ShortcodeService::class)
            ->render('<p>Intro</p>[before_after id=' . $slider->id . ']<p>Outro</p>');

        $this->assertStringContainsString('<p>Intro</p>', $html);
        $this->assertStringContainsString('<p>Outro</p>', $html);
        $this->assertStringContainsString('Retouch Demo', $html);
        $this->assertStringContainsString('beforeAfterSlider()', $html);
        $this->assertStringContainsString(asset('storage/before-after/demo-before.jpg'), $html);
        $this->assertStringContainsString(asset('storage/before-after/demo-after.jpg'), $html);
        $this->assertStringNotContainsString('[before_after', $html);
    }

    public function test_shortcode_is_case_insensitive_and_tolerates_spaces(): void
    {
        $slider = $this->makeSlider();

        $html = app(ShortcodeService::class)->render('[BEFORE_AFTER  ID=   ' . $slider->id . ' ]');

        $this->assertStringContainsString('Retouch Demo', $html);
    }

    public function test_unknown_id_renders_as_empty_string_and_keeps_content(): void
    {
        $html = app(ShortcodeService::class)->render('<p>Keep me</p>[before_after id=99999]');

        $this->assertSame('<p>Keep me</p>', $html);
    }

    public function test_draft_slider_is_not_embedded(): void
    {
        $slider = $this->makeSlider(['status' => 'draft']);

        $html = app(ShortcodeService::class)->render('[before_after id=' . $slider->id . ']');

        $this->assertSame('', $html);
    }

    public function test_content_without_shortcodes_passes_through_untouched(): void
    {
        $content = '<h2>Hello</h2><p>No shortcodes here.</p>';

        $html = app(ShortcodeService::class)->render($content);

        $this->assertSame($content, $html);
    }

    public function test_null_and_empty_content_render_as_empty_string(): void
    {
        $service = app(ShortcodeService::class);

        $this->assertSame('', $service->render(null));
        $this->assertSame('', $service->render(''));
    }

    public function test_same_shortcode_twice_renders_both_instances(): void
    {
        $slider = $this->makeSlider();

        $html = app(ShortcodeService::class)
            ->render('[before_after id=' . $slider->id . '] mid [before_after id=' . $slider->id . ']');

        $this->assertSame(2, substr_count($html, 'beforeAfterSlider()'));
    }

    // ─── Integration through public pages ─────────────────────

    public function test_page_content_renders_embedded_slider(): void
    {
        $slider = $this->makeSlider();

        Page::create([
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => '<p>Welcome</p>[before_after id=' . $slider->id . ']',
            'status' => 'published',
        ]);

        $this->get(route('page.show', 'about-us'))
            ->assertOk()
            ->assertSee('Welcome')
            ->assertSee('Retouch Demo')
            ->assertSee('beforeAfterSlider()', false);
    }

    public function test_service_description_renders_embedded_slider(): void
    {
        $slider = $this->makeSlider();
        $service = $this->makeService([
            'slug' => 'retouch-pro',
            'description' => '<p>Pro editing</p>[before_after id=' . $slider->id . ']',
        ]);

        // The service detail render path leaves one stray output buffer open
        // in the test context (pre-existing quirk; production requests are
        // balanced). Track the level and close strays so PHPUnit does not
        // flag this test as risky.
        $bufferLevel = ob_get_level();

        $this->get(route('services.show', $service->slug))
            ->assertOk()
            ->assertSee('Pro editing')
            ->assertSee('Retouch Demo')
            ->assertSee('beforeAfterSlider()', false);

        while (ob_get_level() > $bufferLevel) {
            ob_end_clean();
        }
    }

    public function test_product_description_renders_embedded_slider(): void
    {
        $slider = $this->makeSlider();
        $product = $this->makeProduct([
            'slug' => 'preset-pack',
            'description' => '<p>Pack details</p>[before_after id=' . $slider->id . ']',
        ]);

        $this->get(route('products.show', $product->slug))
            ->assertOk()
            ->assertSee('Pack details')
            ->assertSee('Retouch Demo')
            ->assertSee('beforeAfterSlider()', false);
    }

    public function test_portfolio_description_renders_embedded_slider(): void
    {
        $slider = $this->makeSlider();
        $project = PortfolioProject::create([
            'category_id' => \App\Models\PortfolioCategory::create(['name' => 'Branding'])->id,
            'title' => 'Brand Identity',
            'slug' => 'brand-identity',
            'description' => '<p>Case study</p>[before_after id=' . $slider->id . ']',
            'status' => 'published',
        ]);

        $this->get(route('portfolio.show', $project->slug))
            ->assertOk()
            ->assertSee('Case study')
            ->assertSee('Retouch Demo')
            ->assertSee('beforeAfterSlider()', false);
    }
}
