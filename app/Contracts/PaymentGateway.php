<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

interface PaymentGateway
{
    /**
     * Gateway identifier (e.g. "manual", "stripe", "bkash", "sslcommerz").
     */
    public function name(): string;

    /**
     * Begin a payment for the given order. Returns an array containing either
     * a "redirect_url" (gateway-hosted checkout) or "instructions" for offline
     * gateways (manual bank transfer).
     */
    public function createPayment(Payment $payment, Order $order): array;

    /**
     * Verify a payment server-side. Never trust a success redirect alone.
     */
    public function verifyPayment(Payment $payment, array $data = []): bool;

    /**
     * Handle an incoming gateway webhook (no-op for offline gateways).
     */
    public function handleWebhook(Request $request);

    /**
     * Whether this gateway supports server-side callbacks.
     */
    public function supportsWebhooks(): bool;
}