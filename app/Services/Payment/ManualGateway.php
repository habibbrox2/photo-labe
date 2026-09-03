<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class ManualGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'manual';
    }

    /**
     * Manual payments have no hosted checkout. Return payment instructions
     * that the customer follows (bank transfer / mobile banking), then staff
     * confirm receipt from the admin panel.
     */
    public function createPayment(Payment $payment, Order $order): array
    {
        $instructions = [
            'title' => 'Bank / Mobile Banking Transfer',
            'lines' => [
                'Please transfer the total amount using the details below.',
                'Include your order number (' . $order->order_number . ') in the payment reference.',
                'Once we confirm receipt, your order will be marked paid and your purchases will be unlocked.',
            ],
        ];

        return [
            'redirect_url' => null,
            'instructions' => $instructions,
        ];
    }

    /**
     * A manual payment is considered verified when staff have marked the
     * payment as paid (PaymentService::confirmPaymentForOrder).
     */
    public function verifyPayment(Payment $payment, array $data = []): bool
    {
        return $payment->status === 'paid';
    }

    /**
     * Manual payments do not receive gateway callbacks.
     */
    public function handleWebhook(Request $request)
    {
        abort(404);
    }

    public function supportsWebhooks(): bool
    {
        return false;
    }
}