<?php

namespace Tests\Feature;

use App\Mail\NewsletterWelcome;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Models\NewsletterSubscriber;
use App\Support\FormTimeTrap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterWelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_subscriber_receives_welcome_email(): void
    {
        Mail::fake();

        $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ])->assertRedirect();

        Mail::assertQueued(NewsletterWelcome::class, function (NewsletterWelcome $mail) {
            return $mail->hasTo('reader@example.com');
        }, 1);
    }

    public function test_welcome_email_contains_unsubscribe_link(): void
    {
        $mailable = new NewsletterWelcome('reader@example.com');

        $rendered = $mailable->render();

        $this->assertStringContainsString('unsubscribe', strtolower((string) $rendered));
        $this->assertStringContainsString(
            NewsletterController::tokenFor('reader@example.com'),
            (string) $rendered
        );
    }

    public function test_welcome_email_is_not_sent_on_duplicate_subscription(): void
    {
        Mail::fake();
        NewsletterSubscriber::create(['email' => 'reader@example.com']);

        $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ])->assertRedirect();

        Mail::assertNothingQueued();
    }

    public function test_resubscribe_after_unsubscribe_sends_welcome_again(): void
    {
        Mail::fake();
        NewsletterSubscriber::create(['email' => 'reader@example.com', 'status' => 'unsubscribed']);

        $this->post(route('newsletter.subscribe'), [
            'email' => 'reader@example.com',
            FormTimeTrap::FIELD => FormTimeTrap::mintValidTokenForTesting(),
        ])->assertRedirect();

        Mail::assertQueued(NewsletterWelcome::class, 1);
    }
}
