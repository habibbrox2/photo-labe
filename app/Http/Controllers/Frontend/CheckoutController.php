<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:500',
            'payment_method' => 'required|in:manual',
        ]);

        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $order = DB::transaction(function () use ($validated, $cart) {
            $subtotal = $cart->total;
            $discount = $cart->discount;
            $tax = 0;
            $total = max(0, $subtotal - $discount + $tax);

            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'currency' => 'USD',
                'quantity' => $cart->items->sum('quantity'),
                'notes' => $validated['address'] ?? null,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'name' => $item->product->title,
                    'description' => $item->product->short_description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);

                // Record purchase for digital products
                Purchase::create([
                    'purchase_number' => 'PUR-' . strtoupper(Str::random(8)),
                    'user_id' => auth()->id(),
                    'product_id' => $item->product_id,
                    'amount' => $item->price * $item->quantity,
                    'currency' => 'USD',
                    'status' => 'pending',
                ]);

                // Increment download count
                Product::where('id', $item->product_id)->increment('download_count', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            return $order;
        });

        // Auto-confirm for manual payment
        $order->update(['status' => 'confirmed']);

        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
    }

    public function success(Order $order)
    {
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('frontend.checkout.success', compact('order'));
    }

    protected function getCart(): ?Cart
    {
        $sessionId = session()->getId();

        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())->with('items.product')->first();
        }

        return Cart::where('session_id', $sessionId)->with('items.product')->first();
    }
}
