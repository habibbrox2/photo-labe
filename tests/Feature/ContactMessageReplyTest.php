<?php

namespace Tests\Feature;

use App\Mail\ContactMessageReply;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageReplyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_reply_via_smtp(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Bulk order question',
            'message' => 'Do you offer volume discounts?',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.contact-messages.reply', $message), [
                'reply_body' => 'Yes, we offer 15% off for 500+ images.',
            ]);

        $response->assertRedirect(route('admin.contact-messages.show', $message));
        $response->assertSessionHas('success');

        // Mailed to the sender (queued — the mailable implements ShouldQueue)
        Mail::assertQueued(ContactMessageReply::class, 1);

        Mail::assertQueued(ContactMessageReply::class, fn ($m) => (
            $m->hasTo('jane@example.com')
            && $m->replyBody === 'Yes, we offer 15% off for 500+ images.'
        ));

        // Reply metadata recorded
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'replied_by' => $admin->id,
        ]);
        $this->assertNotNull($message->fresh()->replied_at);
    }

    public function test_reply_body_is_required(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Hi',
            'message' => 'Hello',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.contact-messages.reply', $message), [
                'reply_body' => '',
            ])
            ->assertSessionHasErrors('reply_body');
    }

    public function test_customer_cannot_send_replies(): void
    {
        Mail::fake();

        $customer = User::factory()->create(['role' => 'customer']);
        $message = ContactMessage::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'subject' => 'Hi',
            'message' => 'Hello',
        ]);

        $this->actingAs($customer)
            ->post(route('admin.contact-messages.reply', $message), [
                'reply_body' => 'sneaky',
            ])
            ->assertForbidden();

        Mail::assertNothingSent();
    }
}
