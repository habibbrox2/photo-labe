@extends('layouts.app')
@section('title', 'Order Confirmed')
@section('content')

<section class="py-16 lg:py-24 bg-white min-h-[60vh]">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="mx-auto w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-7">
            <x-icon name="check" class="w-10 h-10 text-emerald-600" />
        </span>
        <span class="eyebrow">Order received</span>
        <h1 class="mt-5 text-4xl font-extrabold tracking-tight text-gray-900">Thank you — <em class="italic text-accent-600">order confirmed!</em></h1>
        <p class="mt-4 text-gray-500 text-lg">We've received your order{{ auth()->check() ? ' and emailed you a receipt' : '' }}. Check your inbox for next steps.</p>

        @if($payment && isset($payment['instructions']))
        <div class="mt-10 rounded-3xl border border-accent-200 bg-accent-50/60 p-7 text-left">
            <h2 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                <x-icon name="info" class="w-5 h-5 text-accent-600" />
                {{ $payment['instructions']['title'] ?? 'Payment Instructions' }}
            </h2>
            <ul class="space-y-2.5">
                @foreach($payment['instructions']['lines'] ?? [] as $line)
                <li class="flex items-start gap-3 text-sm text-gray-600 leading-relaxed">
                    <span class="mt-0.5 w-5 h-5 rounded-full bg-accent-500/20 flex items-center justify-center shrink-0">
                        <x-icon name="check" class="w-3 h-3 text-accent-700" />
                    </span>
                    <span>{{ $line }}</span>
                </li>
                @endforeach
            </ul>
            <p class="text-xs text-accent-700 mt-4 font-medium">Your downloads unlock as soon as your payment is confirmed.</p>
        </div>
        @endif

        <div class="mt-8 rounded-3xl border border-surface-200 bg-surface-50/60 p-7 text-left">
            <dl class="grid grid-cols-2 gap-x-6 gap-y-5 text-sm">
                <div>
                    <dt class="text-gray-400 text-xs font-medium uppercase tracking-wider">Order number</dt>
                    <dd class="mt-1 font-mono font-bold text-gray-900">{{ $order->order_number }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs font-medium uppercase tracking-wider">Total</dt>
                    <dd class="mt-1 font-bold text-gray-900 text-lg">{{ money($order->total) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs font-medium uppercase tracking-wider">Status</dt>
                    <dd class="mt-1 font-semibold text-emerald-600 capitalize">{{ $order->status }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400 text-xs font-medium uppercase tracking-wider">Date</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</dd>
                </div>
                @if($order->invoice)
                <div>
                    <dt class="text-gray-400 text-xs font-medium uppercase tracking-wider">Invoice</dt>
                    <dd class="mt-1 font-mono font-bold text-gray-900">{{ $order->invoice->invoice_number }}</dd>
                </div>
                @endif
            </dl>

            @if($order->items->count())
            <div class="mt-6 pt-5 border-t border-surface-200">
                <span class="text-gray-400 text-xs font-medium uppercase tracking-wider">Items</span>
                @foreach($order->items as $item)
                <div class="flex justify-between mt-3 text-sm">
                    <span class="text-gray-900 font-medium">{{ $item->name }} <span class="text-gray-400 font-normal">× {{ $item->quantity }}</span></span>
                    <span class="font-semibold text-gray-900">{{ money($item->total_price) }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center">
            @if(auth()->check())
            <a href="{{ route('account.orders') }}" class="btn btn-lg btn-primary">View My Orders</a>
            @endif
            <a href="{{ route('products.index') }}" class="btn btn-lg btn-secondary">Continue Shopping <x-icon name="arrow-right" class="w-4 h-4" /></a>
        </div>
    </div>
</section>
@endsection
