<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class CheckoutAndAdminTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    // ─── Cart ─────────────────────────────────────────────────

    public function test_customer_can_add_a_product_to_cart(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();

        $this->actingAs($customer)
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 2,
            ])
            ->assertRedirect(route('cart.index'));

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price,
        ]);
    }

    public function test_checkout_with_empty_cart_redirects_back_to_cart(): void
    {
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->get(route('checkout.show'))
            ->assertRedirect(route('cart.index'));
    }

    // ─── Checkout (manual gateway) ────────────────────────────

    public function test_customer_can_checkout_creating_order_payment_and_invoice(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();
        $this->makeCart($customer, $product, 2);

        $response = $this->actingAs($customer)->post(route('checkout.process'), [
            'name' => $customer->name,
            'email' => $customer->email,
            'payment_method' => 'manual',
        ]);

        $order = Order::latest()->first();

        $this->assertNotNull($order);
        $this->assertSame($customer->id, $order->user_id);
        $this->assertSame('pending', $order->status);
        $this->assertSame(2, (int) $order->quantity);

        $this->assertDatabaseHas('order_items', ['order_id' => $order->id]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'user_id' => $customer->id,
            'gateway' => 'manual',
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'status' => 'unpaid',
        ]);

        // Pending purchases recorded and cart cleared.
        $this->assertDatabaseHas('purchases', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseCount('cart_items', 0);

        $response->assertRedirect(route('checkout.success', $order));
    }

    public function test_checkout_requires_a_valid_gateway(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();
        $this->makeCart($customer, $product);

        $this->actingAs($customer)
            ->post(route('checkout.process'), [
                'name' => $customer->name,
                'email' => $customer->email,
                'payment_method' => 'nonexistent-gateway',
            ])
            ->assertSessionHasErrors('payment_method');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_customer_cannot_view_someone_elses_checkout_success_page(): void
    {
        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $order = $this->makeOrder($owner);

        $this->actingAs($intruder)
            ->get(route('checkout.success', $order))
            ->assertForbidden();
    }

    public function test_order_owner_can_view_checkout_success_page(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);

        $this->actingAs($customer)
            ->get(route('checkout.success', $order))
            ->assertOk();
    }

    // ─── Payment confirmation (admin marks paid) ──────────────

    public function test_admin_marking_an_order_paid_confirms_payment_and_unlocks_purchases(): void
    {
        $customer = $this->makeCustomer();
        $product = $this->makeProduct();
        $this->makeCart($customer, $product, 1);

        $this->actingAs($customer)->post(route('checkout.process'), [
            'name' => $customer->name,
            'email' => $customer->email,
            'payment_method' => 'manual',
        ]);

        $order = Order::latest()->first();
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->put(route('admin.orders.update', $order), [
                'status' => 'paid',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        // Order + payment marked paid, transaction recorded, invoice finalized.
        $this->assertSame('paid', $order->fresh()->status);

        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertNotNull($payment->fresh()->transaction_id);
        $this->assertDatabaseHas('transactions', [
            'payment_id' => $payment->id,
            'type' => 'charge',
            'status' => 'succeeded',
        ]);

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'status' => 'paid',
        ]);

        // Purchases unlocked for the customer.
        $this->assertDatabaseHas('purchases', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'status' => 'completed',
        ]);
    }

    public function test_marking_an_already_paid_order_does_not_duplicate_transactions(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer, ['status' => 'pending']);
        $payment = \App\Models\Payment::create([
            'user_id' => $customer->id,
            'order_id' => $order->id,
            'amount' => $order->total,
            'currency' => 'USD',
            'gateway' => 'manual',
            'status' => 'pending',
        ]);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)->put(route('admin.orders.update', $order), ['status' => 'paid']);
        $this->actingAs($admin)->put(route('admin.orders.update', $order), ['status' => 'paid']);

        $this->assertSame('paid', $payment->fresh()->status);
        $this->assertSame(1, $payment->transactions()->count());
    }

    // ─── Purchases & downloads ────────────────────────────────

    public function test_customer_can_download_their_completed_purchase(): void
    {
        Storage::fake('public');

        $customer = $this->makeCustomer();
        $product = $this->makeProduct();
        $purchase = $this->makeCompletedPurchase($customer, $product);

        Storage::disk('public')->put('products/pack.zip', 'content');

        $file = ProductFile::create([
            'product_id' => $product->id,
            'file_name' => 'pack.zip',
            'file_path' => 'products/pack.zip',
        ]);

        $this->actingAs($customer)
            ->get(route('account.purchases.download', [$purchase, $file]))
            ->assertOk()
            ->assertDownload('pack.zip');

        $this->assertSame(1, $purchase->fresh()->download_count);
    }

    public function test_customer_cannot_download_someone_elses_purchase(): void
    {
        Storage::fake('public');

        $owner = $this->makeCustomer();
        $intruder = $this->makeCustomer();
        $product = $this->makeProduct();
        $purchase = $this->makeCompletedPurchase($owner, $product);

        Storage::disk('public')->put('products/pack.zip', 'content');

        $file = ProductFile::create([
            'product_id' => $product->id,
            'file_name' => 'pack.zip',
            'file_path' => 'products/pack.zip',
        ]);

        $this->actingAs($intruder)
            ->get(route('account.purchases.download', [$purchase, $file]))
            ->assertForbidden();
    }

    // ─── Admin authorization ──────────────────────────────────

    public function test_customer_cannot_access_admin_pages(): void
    {
        $customer = $this->makeCustomer();

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('admin.orders.index'))
            ->assertForbidden();
    }

    public function test_editor_cannot_access_admin_only_pages(): void
    {
        $editor = $this->makeEditor();

        $this->actingAs($editor)
            ->get(route('admin.orders.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_pages(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertOk();
    }

    public function test_admin_can_delete_an_order(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->delete(route('admin.orders.destroy', $order))
            ->assertRedirect(route('admin.orders.index'));

        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    public function test_completing_an_order_sets_completed_at(): void
    {
        $customer = $this->makeCustomer();
        $order = $this->makeOrder($customer, ['status' => 'paid']);
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->put(route('admin.orders.update', $order), [
                'status' => 'completed',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertSame('completed', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->completed_at);
    }
}
