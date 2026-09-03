<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Payment Gateway
    |--------------------------------------------------------------------------
    |
    | The default gateway used at checkout. Set PAYMENT_GATEWAY in .env to
    | switch (e.g. PAYMENT_GATEWAY=stripe). New gateways are registered below.
    |
    */

    'default' => env('PAYMENT_GATEWAY', 'manual'),

    /*
    |--------------------------------------------------------------------------
    | Available Gateways
    |--------------------------------------------------------------------------
    |
    | Map gateway identifiers to their implementations. The manual gateway
    | works out of the box (bank transfer confirmed by staff) and requires no
    | external credentials. Stripe/bKash/SSLCommerz implementations can be
    | dropped in as classes implementing App\Contracts\PaymentGateway.
    |
    */

    'gateways' => [
        'manual' => \App\Services\Payment\ManualGateway::class,
        // 'stripe' => \App\Services\Payment\StripeGateway::class,
        // 'bkash' => \App\Services\Payment\BkashGateway::class,
        // 'sslcommerz' => \App\Services\Payment\SslCommerzGateway::class,
    ],

];