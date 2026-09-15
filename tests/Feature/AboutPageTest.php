<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    private function makeAboutPage(array $attributes = []): Page
    {
        return Page::create(array_merge([
            'title' => 'About PhotoLabe',
            'slug' => 'about',
            'system_key' => Page::SYSTEM_ABOUT,
            'eyebrow' => 'Our Studio',
            'subtitle' => 'A studio that retouches.',
            'content' => '<h2>Our Story</h2><p>EDITABLE-ABOUT-MARKER</p>',
            'status' => 'published',
        ], $attributes));
    }

    public function test_about_renders_the_managed_page_content(): void
    {
        $this->makeAboutPage();

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('EDITABLE-ABOUT-MARKER', false)
            ->assertSee('About PhotoLabe')
            ->assertSee('Our Studio')
            ->assertSee('A studio that retouches.');
    }

    public function test_about_falls_back_to_built_in_copy_when_no_page_exists(): void
    {
        // Fresh install: composer setup migrates but does not seed, so /about must
        // still render rather than 404.
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('Our Story')
            ->assertSee('PhotoLabe is a professional photo editing and creative design studio', false)
            ->assertDontSee('EDITABLE-ABOUT-MARKER', false);
    }

    public function test_drafting_the_about_page_takes_it_offline(): void
    {
        $this->makeAboutPage(['status' => 'draft']);

        $this->get(route('about'))->assertNotFound();
    }

    public function test_admin_can_update_the_about_page_and_see_it_live(): void
    {
        $page = $this->makeAboutPage();
        $admin = $this->makeAdmin();

        // The admin form must point editors at the page it actually powers.
        $this->actingAs($admin)->get(route('admin.pages.edit', $page))
            ->assertOk()
            ->assertSee('Powers')
            ->assertSee(route('about'), false)
            ->assertSee('name="eyebrow"', false)
            ->assertSee('name="subtitle"', false)
            ->assertSee('name="slug"', false);

        $this->actingAs($admin)
            ->put(route('admin.pages.update', $page), [
                'title' => 'About PhotoLabe',
                'slug' => 'about',
                'eyebrow' => 'Who we are',
                'subtitle' => 'Updated lead sentence.',
                'content' => '<h2>Our Story</h2><p>UPDATED-ABOUT-COPY</p>',
                'template' => 'default',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('UPDATED-ABOUT-COPY', false)
            ->assertSee('Who we are')
            ->assertSee('Updated lead sentence.')
            ->assertDontSee('EDITABLE-ABOUT-MARKER', false);
    }

    public function test_renaming_a_page_does_not_move_it(): void
    {
        $page = $this->makeAboutPage();

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.pages.update', $page), [
                'title' => 'About the Studio',
                'slug' => $page->slug,
                'content' => $page->content,
                'template' => 'default',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $this->assertSame('about', $page->fresh()->slug);
        $this->get(route('about'))->assertOk()->assertSee('About the Studio');
    }

    public function test_system_page_cannot_be_deleted(): void
    {
        $page = $this->makeAboutPage();

        $this->actingAs($this->makeAdmin())
            ->delete(route('admin.pages.destroy', $page))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pages', ['id' => $page->id, 'deleted_at' => null]);
    }

    public function test_pages_index_marks_system_pages_as_fixed(): void
    {
        $about = $this->makeAboutPage();
        $ordinary = Page::create([
            'title' => 'Shipping Policy',
            'slug' => 'shipping-policy',
            'content' => '<p>Policy</p>',
            'status' => 'published',
        ]);

        $this->actingAs($this->makeAdmin())->get(route('admin.pages.index'))
            ->assertOk()
            ->assertSee('Fixed page')
            ->assertSee('Shipping Policy');
    }

    public function test_ordinary_pages_can_still_be_deleted(): void
    {
        $page = Page::create([
            'title' => 'Shipping Policy',
            'slug' => 'shipping-policy',
            'status' => 'published',
        ]);

        $this->actingAs($this->makeAdmin())
            ->delete(route('admin.pages.destroy', $page))
            ->assertRedirect(route('admin.pages.index'));

        $this->assertSoftDeleted('pages', ['id' => $page->id]);
    }

    public function test_admin_can_create_a_page_with_hero_copy_and_a_chosen_slug(): void
    {
        $this->actingAs($this->makeAdmin())
            ->post(route('admin.pages.store'), [
                'title' => 'Terms of Service',
                'slug' => 'Legal Terms',
                'eyebrow' => 'Legal',
                'subtitle' => 'The small print.',
                'content' => '<p>Terms</p>',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.pages.index'));

        $this->assertDatabaseHas('pages', ['slug' => 'legal-terms', 'eyebrow' => 'Legal']);
        $this->get(route('page.show', 'legal-terms'))->assertOk()->assertSee('The small print.');
    }

    public function test_duplicate_slugs_are_rejected(): void
    {
        $this->makeAboutPage();

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.pages.store'), [
                'title' => 'Another page',
                'slug' => 'about',
                'status' => 'published',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_page_seeder_publishes_about_without_clobbering_edits(): void
    {
        $this->seed(PageSeeder::class);

        $page = Page::systemKey(Page::SYSTEM_ABOUT)->firstOrFail();
        $this->assertSame('published', $page->status);
        $this->assertSame('about', $page->slug);
        $this->get(route('about'))->assertOk()->assertSee('Our Story');

        // An editor's later edit must survive another seed run.
        $page->update(['content' => '<p>EDITED-BY-HAND</p>']);
        $this->seed(PageSeeder::class);

        $this->assertSame('<p>EDITED-BY-HAND</p>', $page->fresh()->content);
    }
}
