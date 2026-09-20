<?php

namespace Tests\Feature;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class PublicSiteCmsTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    public function test_admin_can_save_blocks_preview_a_draft_and_publish_it(): void
    {
        $page = Page::create(['title' => 'Landing', 'slug' => 'landing', 'status' => 'draft']);
        $admin = $this->makeAdmin();
        $payload = [
            'title' => 'Landing', 'slug' => 'landing', 'template' => 'default', 'status' => 'draft',
            'blocks' => [['type' => 'hero', 'data' => ['title' => 'Draft-only headline', 'subtitle' => 'Preview copy']]],
        ];

        $this->get(route('admin.public-site.preview', $page))->assertRedirect(route('login'));
        $this->actingAs($admin)->put(route('admin.pages.update', $page), $payload)->assertRedirect();
        $this->get(route('page.show', $page->slug))->assertNotFound();
        $this->actingAs($admin)->get(route('admin.public-site.preview', $page))->assertOk()->assertSee('Draft-only headline');

        $payload['status'] = 'published';
        $this->actingAs($admin)->put(route('admin.pages.update', $page), $payload)->assertRedirect();
        $this->get(route('page.show', $page->slug))->assertOk()->assertSee('Draft-only headline');
    }

    public function test_rejects_insecure_external_cta_url(): void
    {
        $page = Page::create(['title' => 'Landing', 'slug' => 'landing', 'status' => 'draft']);
        $this->actingAs($this->makeAdmin())->put(route('admin.pages.update', $page), [
            'title' => 'Landing', 'slug' => 'landing', 'template' => 'default', 'status' => 'draft',
            'blocks' => [['type' => 'cta', 'data' => ['url' => 'http://example.com']]],
        ])->assertSessionHasErrors('blocks');
    }
}
