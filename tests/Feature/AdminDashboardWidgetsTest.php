<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class AdminDashboardWidgetsTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    // ─── Notifications widget ─────────────────────────────────

    public function test_admin_sees_own_notifications_with_unread_count(): void
    {
        $admin = $this->makeAdmin();

        $admin->notifications()->createMany([
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\Notifications\OrderStatusNotification',
                'data' => [
                    'title' => 'Order shipped',
                    'message' => 'Order ORD-TEST0001 was marked completed.',
                ],
            ],
            [
                'id' => (string) Str::uuid(),
                'type' => 'App\Notifications\QuoteReceivedNotification',
                'data' => [
                    'title' => 'Quote received',
                    'message' => 'A customer requested a quote.',
                ],
                'read_at' => now(),
            ],
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Order shipped');
        $response->assertSee('Order ORD-TEST0001 was marked completed.');
        $response->assertSee('Quote received');
        $response->assertSee('1 new');
        $response->assertSee('Mark all as read');
    }

    public function test_notifications_widget_shows_empty_state_for_new_admin(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('No notifications yet.');
        $response->assertDontSee('Mark all as read');
    }

    // ─── Recent quotes widget ─────────────────────────────────

    public function test_recent_quotes_widget_lists_quotes_with_status_badges(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();
        $service = $this->makeService();

        $pending = $this->makeQuote($customer, [
            'name' => 'Alice Pending',
            'service_id' => $service->id,
            'status' => 'pending',
        ]);
        $accepted = $this->makeQuote($customer, [
            'name' => 'Bob Accepted',
            'status' => 'accepted',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Alice Pending');
        $response->assertSee('Bob Accepted');
        $response->assertSee('Pending');
        $response->assertSee('Accepted');
        $response->assertSee(route('admin.quotes.show', $pending));
        $response->assertSee(route('admin.quotes.show', $accepted));
    }

    public function test_recent_quotes_widget_shows_empty_state_without_quotes(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('No quotes yet.');
    }

    public function test_recent_quotes_widget_shows_at_most_five_quotes(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();

        for ($i = 1; $i <= 7; $i++) {
            $quote = $this->makeQuote($customer, ['name' => "Quote Number {$i}"]);
            // Distinct timestamps: identical created_at values would make
            // latest()->take(5) non-deterministic on SQLite.
            $quote->forceFill(['created_at' => now()->addMinutes($i)])->save();
        }

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Quote Number 7');
        $response->assertSee('Quote Number 3');
        $response->assertDontSee('Quote Number 2');
        $response->assertDontSee('Quote Number 1');
    }

    // ─── Recent orders widget ─────────────────────────────────

    public function test_recent_orders_widget_lists_orders_with_status_badges(): void
    {
        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer(['name' => 'Carol Customer']);

        $pending = $this->makeOrder($customer, ['status' => 'pending']);
        $completed = $this->makeOrder($customer, ['status' => 'completed']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee($pending->order_number);
        $response->assertSee($completed->order_number);
        $response->assertSee('Carol Customer');
        $response->assertSee('Pending');
        $response->assertSee('Completed');
        $response->assertSee(route('admin.orders.show', $pending));
        $response->assertSee(route('admin.orders.show', $completed));
    }

    public function test_recent_orders_widget_shows_empty_state_without_orders(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('No orders yet.');
    }

    // ─── Stats widgets ────────────────────────────────────────

    public function test_stat_widgets_aggregate_counts_and_revenue(): void
    {
        // Pin the display currency so the revenue assertion is explicit.
        \App\Models\Setting::set('currency', 'USD');

        $admin = $this->makeAdmin();
        $customer = $this->makeCustomer();

        $this->makeOrder($customer, ['status' => 'completed', 'total' => 120.50]);
        $this->makeOrder($customer, ['status' => 'completed', 'total' => 79.50]);
        $this->makeOrder($customer, ['status' => 'pending', 'total' => 999.00]);
        $this->makeQuote($customer, ['status' => 'pending']);
        $this->makeQuote($customer, ['status' => 'accepted']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk()
            ->assertSee('$200.00')        // revenue: completed orders only (120.50 + 79.50)
            ->assertDontSee('$999.00')    // pending order total must not count as revenue
            ->assertSee('>3</p>', false)  // total orders
            ->assertSee('>1</p>', false); // pending quotes and customers
    }
}
