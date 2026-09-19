<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use App\Notifications\ContactMessageNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_saves_message_and_notifies_staff(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'super_admin']);
        User::factory()->create(['role' => 'customer']);

        $response = $this->post(route('contact.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Bulk order question',
            'message' => 'Do you offer volume discounts for 500+ images?',
            \App\Support\FormTimeTrap::FIELD => \App\Support\FormTimeTrap::mintValidTokenForTesting(),
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Message persisted
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'jane@example.com',
            'subject' => 'Bulk order question',
            'status' => 'new',
        ]);

        Notification::assertSentTimes(ContactMessageNotification::class, 1);

        Notification::assertSentTo(
            $admin,
            ContactMessageNotification::class,
            fn ($n) => $n->contactMessage->email === 'jane@example.com'
        );

        // The customer should NOT be notified.
        Notification::assertNotSentTo(
            User::where('role', 'customer')->first(),
            ContactMessageNotification::class
        );
    }

    public function test_admin_can_view_inbox_and_mark_messages_read(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Bulk order question',
            'message' => 'Do you offer volume discounts?',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.contact-messages.index'))
            ->assertOk()
            ->assertSee('jane@example.com');

        $this->actingAs($admin)
            ->get(route('admin.contact-messages.show', $message))
            ->assertOk()
            ->assertSee('volume discounts');

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'read',
            'read_by' => $admin->id,
        ]);
    }

    public function test_customer_cannot_access_contact_inbox(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($customer)
            ->get(route('admin.contact-messages.index'))
            ->assertForbidden();
    }

    public function test_admin_can_archive_a_message(): void
    {
        Notification::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $message = ContactMessage::create([
            'name' => 'Spam Bot',
            'email' => 'spam@example.com',
            'subject' => 'Buy now',
            'message' => 'Cheap SEO services.',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.contact-messages.archive', $message))
            ->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'archived',
        ]);
    }

    public function test_honeypot_silently_rejects_bots(): void
    {
        Notification::fake();

        $response = $this->post(route('contact.store'), [
            'name' => 'Bot Botson',
            'email' => 'bot@spam.com',
            'subject' => 'Buy now',
            'message' => 'Cheap SEO services.',
            'website' => 'https://spam.example', // honeypot filled = bot
        ]);

        // Looks like success to the bot, but nothing is stored or sent.
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);
        Notification::assertNothingSent();
    }

    public function test_time_trap_rejects_submissions_that_are_too_fast(): void
    {
        Notification::fake();

        // Token minted "now" — an immediate POST is faster than a human.
        $token = \App\Support\FormTimeTrap::token();

        $response = $this->post(route('contact.store'), [
            'name' => 'Fast Bot',
            'email' => 'bot@spam.com',
            'subject' => 'Hi',
            'message' => 'Instant submit.',
            \App\Support\FormTimeTrap::FIELD => $token,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('contact_messages', 0);
        Notification::assertNothingSent();
    }

    public function test_time_trap_accepts_realistic_timing(): void
    {
        Notification::fake();

        User::factory()->create(['role' => 'super_admin']);

        // Forge a token "opened" 30 seconds ago.
        $token = Crypt::encryptString((string) now()->subSeconds(30)->timestamp);

        $response = $this->post(route('contact.store'), [
            'name' => 'Real Human',
            'email' => 'human@example.com',
            'subject' => 'Hello',
            'message' => 'A real message.',
            \App\Support\FormTimeTrap::FIELD => $token,
        ]);

        $this->assertDatabaseCount('contact_messages', 1);
        Notification::assertSentTimes(ContactMessageNotification::class, 1);
    }

    public function test_time_trap_rejects_missing_or_tampered_token(): void
    {
        Notification::fake();

        $response = $this->post(route('contact.store'), [
            'name' => 'Forged Bot',
            'email' => 'bot@spam.com',
            'subject' => 'Hi',
            'message' => 'No token.',
            \App\Support\FormTimeTrap::FIELD => 'tampered-garbage',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('contact_messages', 0);
        Notification::assertNothingSent();
    }

    public function test_contact_form_validates_input(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'subject' => '',
            'message' => '',
            \App\Support\FormTimeTrap::FIELD => \App\Support\FormTimeTrap::mintValidTokenForTesting(),
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }
}
