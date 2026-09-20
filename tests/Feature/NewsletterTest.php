<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Support\FormTimeTrap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_subscribe_from_footer(): void
    {
        $response = $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'reader@example.com',
            'status' => 'subscribed',
        ]);
    }

    public function test_duplicate_subscription_is_flagged_not_duplicated(): void
    {
        NewsletterSubscriber::create(['email' => 'reader@example.com']);

        $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ])->assertRedirect();

        $this->assertDatabaseCount('newsletter_subscribers', 1);
    }

    public function test_honeypot_submission_is_silently_ignored(): void
    {
        $this->post(route('newsletter.subscribe'), [
            'email' => 'bot@example.com',
            'website' => 'http://spam.example',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ])->assertRedirect();

        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_garbage_time_token_is_silently_ignored(): void
    {
        $this->post(route('newsletter.subscribe'), [
            'email' => 'bot@example.com',
            FormTimeTrap::FIELD => 'garbage-token',
        ])->assertRedirect();

        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->from('/')->post(route('newsletter.subscribe'), [
            'email' => 'not-an-email',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ]);

        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_unsubscribing_works_with_valid_token(): void
    {
        $subscriber = NewsletterSubscriber::create(['email' => 'reader@example.com']);
        $token = \App\Http\Controllers\Frontend\NewsletterController::tokenFor('reader@example.com');

        $this->get(route('newsletter.unsubscribe', ['email' => 'reader@example.com', 'token' => $token]))
            ->assertOk()
            ->assertSee('unsubscribed');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'id' => $subscriber->id,
            'status' => 'unsubscribed',
        ]);
    }

    public function test_unsubscribe_with_bad_token_is_forbidden(): void
    {
        $this->get(route('newsletter.unsubscribe', ['email' => 'reader@example.com', 'token' => 'nope']))
            ->assertForbidden();
    }

    public function test_resubscribe_after_unsubscribe_works(): void
    {
        NewsletterSubscriber::create(['email' => 'reader@example.com', 'status' => 'unsubscribed']);

        $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ])->assertRedirect();

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'reader@example.com',
            'status' => 'subscribed',
        ]);
    }
}
