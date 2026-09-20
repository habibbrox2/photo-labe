<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderPlacedNotification;
use Tests\Concerns\InteractsWithFixtures;
use Tests\TestCase;

class OrderConfirmationEmailTest extends TestCase
{
    use RefreshDatabase, InteractsWithFixtures;

    public function test_customer_receives_order_confirmation_email_at_checkout(): void
    {
        Notification::fake();

        $customer = User::factory()->create(['email' => 'buyer@example.com']);
        $product = Product::factory()->create(['title' => 'Cinematic Film Presets', 'price' => 29.99]);

        $this->actingAs($customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->post(route('checkout.process'), [
            'name' => 'Test Buyer',
            'email' => 'buyer@example.com',
            'payment_method' => 'manual',
        ])->assertRedirect();

        Notification::assertSentTo(
            new \Illuminate\Notifications\AnonymousNotifiable,
            OrderPlacedNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'buyer@example.com';
            }
        );
    }

    public function test_confirmation_email_contains_order_details(): void
    {
        $customer = User::factory()->create(['name' => 'Jane']);
        $product = Product::factory()->create(['title' => 'Skin Actions', 'price' => 19.99]);

        $this->actingAs($customer)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $order = $this->makeOrder($customer, ['user_id' => $customer->id]);
        $order->items()->create([
            'name' => 'Skin Actions',
            'quantity' => 2,
            'unit_price' => 19.99,
            'total_price' => 39.98,
        ]);

        $rendered = (new OrderPlacedNotification($order->load('items', 'user')))
            ->toMail($customer)
            ->render();

        $this->assertStringContainsString('Jane', (string) $rendered);
        $this->assertStringContainsString('Skin Actions', (string) $rendered);
    }
}
