<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentService
{
    /**
     * Resolve a gateway instance by identifier.
     */
    public function gateway(?string $name = null): PaymentGateway
    {
        $name = $name ?: config('payment.default');

        $class = config("payment.gateways.{$name}");

        if (! $class || ! class_exists($class)) {
            throw new RuntimeException("Payment gateway [{$name}] is not configured.");
        }

        return app($class);
    }

    /**
     * Available gateway identifiers for the checkout form.
     */
    public function available(): array
    {
        return array_keys(config('payment.gateways', []));
    }

    /**
     * Create a payment + invoice for an order (called inside the checkout
     * transaction). Returns the payment record.
     */
    public function createForOrder(Order $order, string $gateway): Payment
    {
        $payment = Payment::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'amount' => $order->total,
            'currency' => $order->currency,
            'gateway' => $gateway,
            'status' => 'pending',
        ]);

        Invoice::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'payment_id' => $payment->id,
            'subtotal' => $order->subtotal,
            'tax' => $order->tax,
            'total' => $order->total,
            'status' => 'unpaid',
            'due_date' => now()->addDays(7),
        ]);

        return $payment;
    }

    /**
     * Confirm a payment after server-side verification. Idempotent: repeated
     * calls for an already-paid order are no-ops. Marks the payment paid,
     * records a transaction, completes the digital product purchases, and
     * finalizes the invoice.
     */
    public function confirmPaymentForOrder(Order $order): ?Payment
    {
        $payment = $order->payments()->where('status', '!=', 'paid')->latest()->first();

        if (! $payment) {
            return null;
        }

        $payment->update([
            'status' => 'paid',
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'paid_at' => now(),
            'gateway_response' => array_merge($payment->gateway_response ?? [], [
                'verified_by' => auth()->id() ?? 'system',
                'verified_at' => now()->toDateTimeString(),
            ]),
        ]);

        $payment->transactions()->create([
            'type' => 'charge',
            'amount' => $payment->amount,
            'status' => 'succeeded',
            'gateway_transaction_id' => $payment->transaction_id,
        ]);

        // Unlock purchased digital products
        Purchase::where('payment_id', $payment->id)
            ->where('status', 'pending')
            ->update(['status' => 'completed', 'completed_at' => now()]);

        // Finalize the invoice
        $payment->invoice?->update([
            'status' => 'paid',
            'paid_at' => now()->toDateString(),
        ]);

        $order->update(['status' => 'paid']);

        return $payment;
    }
}