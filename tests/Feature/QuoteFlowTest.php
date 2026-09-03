<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class QuoteFlowTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    // ─── Submission ───────────────────────────────────────────

    public function test_quote_submission_requires_login(): void
    {
        $this->post('/get-a-quote', [
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'quantity' => 1,
            'requirements' => 'Please retouch a photo.',
        ])->assertRedirect(route('login'));
    }

    public function test_customer_can_submit_quote_request(): void
    {
        $service = $this->makeService();
        $customer = $this->makeCustomer();

        $response = $this->actingAs($customer)->post('/get-a-quote', [
            'name' => $customer->name,
            'email' => $customer->email,
            'service_id' => $service->id,
            'quantity' => 3,
            'deadline' => now()->addDays(7)->toDateString(),
            'requirements' => 'Retouch 3 product photos with background removal.',
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('quotes', [
            'user_id' => $customer->id,
            'service_id' => $service->id,
            'quantity' => 3,
            'status' => 'pending',
        ]);
    }

    public function test_quote_submission_validates_requirements(): void
    {
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->post('/get-a-quote', [
                'name' => $customer->name,
                'email' => $customer->email,
                'quantity' => 1,
                'requirements' => '',
            ])
            ->assertSessionHasErrors('requirements');

        $this->assertDatabaseCount('quotes', 0);
    }

    public function test_staff_are_notified_when_a_quote_is_submitted(): void
    {
        Notification::fake();

        $admin = $this->makeAdmin();
        $editor = $this->makeEditor();
        $customer = $this->makeCustomer();

        $this->actingAs($customer)->post('/get-a-quote', [
            'name' => $customer->name,
            'email' => $customer->email,
            'quantity' => 1,
            'requirements' => 'Please retouch a photo.',
        ]);

        Notification::assertSentTo(
            [$admin, $editor],
            \App\Notifications\QuoteReceivedNotification::class,
        );
    }

    // ─── Ownership guards ─────────────────────────────────────

    public function test_customer_can_view_their_own_quote(): void
    {
        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer);

        $this->actingAs($customer)
            ->get(route('account.quotes.show', $quote))
            ->assertOk();
    }

    public function test_customer_cannot_view_another_customers_quote(): void
    {
        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $quote = $this->makeQuote($owner);

        $this->actingAs($intruder)
            ->get(route('account.quotes.show', $quote))
            ->assertForbidden();
    }

    // ─── Accept / reject ──────────────────────────────────────

    public function test_customer_can_accept_a_quoted_price_creating_an_order(): void
    {
        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer, [
            'status' => 'quoted',
            'quoted_price' => 120.00,
        ]);

        $response = $this->actingAs($customer)
            ->post(route('account.quotes.accept', $quote));

        $order = Order::where('quote_id', $quote->id)->first();

        $this->assertNotNull($order);
        $this->assertSame(120.00, (float) $order->total);
        $this->assertSame('pending', $order->status);

        $this->assertSame('converted', $quote->fresh()->status);

        $response->assertRedirect(route('account.orders.show', $order));
    }

    public function test_customer_cannot_accept_an_unpriced_quote(): void
    {
        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer, ['status' => 'pending']);

        $this->actingAs($customer)
            ->post(route('account.quotes.accept', $quote))
            ->assertSessionHas('error');

        $this->assertSame('pending', $quote->fresh()->status);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_cannot_accept_someone_elses_quote(): void
    {
        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $quote = $this->makeQuote($owner, ['status' => 'quoted', 'quoted_price' => 50]);

        $this->actingAs($intruder)
            ->post(route('account.quotes.accept', $quote))
            ->assertForbidden();

        $this->assertSame('quoted', $quote->fresh()->status);
    }

    public function test_customer_can_reject_a_quoted_price(): void
    {
        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer, ['status' => 'quoted', 'quoted_price' => 999]);

        $this->actingAs($customer)
            ->post(route('account.quotes.reject', $quote))
            ->assertRedirect(route('account.quotes'));

        $this->assertSame('rejected', $quote->fresh()->status);
    }

    // ─── Admin quote management ───────────────────────────────

    public function test_admin_can_price_a_quote_and_customer_is_notified(): void
    {
        Notification::fake();

        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->put(route('admin.quotes.update', $quote), [
                'status' => 'quoted',
                'quoted_price' => 75.00,
                'admin_notes' => 'Includes 2 free revisions.',
            ])
            ->assertRedirect(route('admin.quotes.show', $quote));

        $this->assertSame('quoted', $quote->fresh()->status);
        $this->assertSame('75.00', $quote->fresh()->quoted_price);

        Notification::assertSentTo(
            $customer,
            \App\Notifications\QuoteStatusUpdatedNotification::class,
        );
    }

    public function test_admin_can_convert_a_quote_to_an_order(): void
    {
        $customer = $this->makeCustomer();
        $service = $this->makeService();
        $quote = $this->makeQuote($customer, [
            'service_id' => $service->id,
            'status' => 'accepted',
            'quoted_price' => 200.00,
        ]);
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)
            ->post(route('admin.quotes.convert', $quote));

        $order = Order::where('quote_id', $quote->id)->first();

        $this->assertNotNull($order);
        $this->assertSame($customer->id, $order->user_id);
        $this->assertSame('200.00', $order->total);

        $this->assertSame('converted', $quote->fresh()->status);

        $response->assertRedirect(route('admin.orders.show', $order));
    }

    public function test_admin_cannot_convert_an_already_converted_quote(): void
    {
        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer, ['status' => 'converted']);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->post(route('admin.quotes.convert', $quote))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_cannot_access_admin_quote_routes(): void
    {
        $customer = $this->makeCustomer();
        $quote = $this->makeQuote($customer);
        $admin = $this->makeAdmin();

        // A customer browsing another customer's quote through admin URLs is forbidden.
        $this->actingAs($admin)->get(route('admin.quotes.index'))->assertOk();

        $this->actingAs($customer)
            ->get(route('admin.quotes.index'))
            ->assertForbidden();
    }
}
