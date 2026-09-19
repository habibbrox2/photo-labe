<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardContactWidgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_contact_inbox_widget_with_new_badge(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        ContactMessage::create(['name' => 'Jane', 'email' => 'jane@example.com', 'subject' => 'Bulk order question', 'message' => 'Hi']);
        ContactMessage::create(['name' => 'Bob', 'email' => 'bob@example.com', 'subject' => 'Turnaround time?', 'message' => 'Hello']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Contact Inbox')
            ->assertSee('2 new')
            ->assertSee('Bulk order question')
            ->assertSee('Turnaround time?');
    }

    public function test_dashboard_widget_shows_replied_indicator(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);
        ContactMessage::create([
            'name' => 'Jane', 'email' => 'jane@example.com', 'subject' => 'Old question',
            'message' => 'Hi', 'status' => 'read', 'read_at' => now(),
            'replied_by' => $admin->id, 'replied_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Old question')
            ->assertSee('Replied');
    }

    public function test_dashboard_widget_shows_empty_state_without_messages(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('No contact messages yet.');
    }
}
