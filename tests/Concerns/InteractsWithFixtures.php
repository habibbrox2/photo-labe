<?php

namespace Tests\Concerns;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Purchase;
use App\Models\Quote;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait InteractsWithFixtures
{
    protected function makeUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'email_verified_at' => now(),
            'role' => 'customer',
        ], $attributes));
    }

    protected function makeCustomer(array $attributes = []): User
    {
        return $this->makeUser($attributes);
    }

    protected function makeAdmin(array $attributes = []): User
    {
        return $this->makeUser(array_merge(['role' => 'super_admin'], $attributes));
    }

    protected function makeEditor(array $attributes = []): User
    {
        return $this->makeUser(array_merge(['role' => 'editor'], $attributes));
    }

    protected function makeService(array $attributes = []): Service
    {
        $category = \App\Models\ServiceCategory::first()
            ?? \App\Models\ServiceCategory::create(['name' => 'Editing']);

        return Service::create(array_merge([
            'category_id' => $category->id,
            'title' => 'Photo Retouching',
            'status' => 'published',
            'sort_order' => 1,
        ], $attributes));
    }

    protected function makeQuote(User $user, array $attributes = []): Quote
    {
        return Quote::create(array_merge([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'quantity' => 1,
            'requirements' => 'Please retouch 5 photos for my wedding album.',
            'status' => 'pending',
        ], $attributes));
    }

    protected function makeOrder(User $user, array $attributes = []): Order
    {
        return Order::create(array_merge([
            'user_id' => $user->id,
            'subtotal' => 50,
            'total' => 50,
            'status' => 'pending',
        ], $attributes));
    }

    protected function makeProduct(array $attributes = []): Product
    {
        $category = ProductCategory::first()
            ?? ProductCategory::create(['name' => 'Presets', 'is_active' => true]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'title' => 'Cinematic Preset Pack',
            'price' => 29.99,
            'status' => 'published',
        ], $attributes));
    }

    protected function makeCart(User $user, Product $product, int $quantity = 1): Cart
    {
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        return $cart->fresh();
    }

    protected function fakeOrderFileDisk(): void
    {
        Storage::fake('local');
        Storage::fake('public');
    }

    protected function makeOrderFile(Order $order, string $type = 'input', array $attributes = []): OrderFile
    {
        $this->fakeOrderFileDisk();

        $storedName = 'file-' . str()->random(8) . '.zip';

        Storage::disk('local')->put("private/orders/{$order->id}/{$type}/{$storedName}", 'test-content');

        return OrderFile::create(array_merge([
            'order_id' => $order->id,
            'original_name' => 'delivery.zip',
            'stored_name' => $storedName,
            'file_path' => "private/orders/{$order->id}/{$type}/{$storedName}",
            'mime_type' => 'application/zip',
            'file_size' => 12,
            'type' => $type,
        ], $attributes));
    }

    protected function makeCompletedPurchase(User $user, Product $product): Purchase
    {
        return Purchase::create([
            'purchase_number' => 'PUR-TEST' . strtoupper(str()->random(6)),
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => $product->price,
            'currency' => 'USD',
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    protected function fakeZipUpload(string $name = 'delivery.zip'): UploadedFile
    {
        return UploadedFile::fake()->create($name, 10, 'application/zip');
    }
}
