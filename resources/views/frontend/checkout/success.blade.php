@extends('layouts.app')
@section('title', 'Order Confirmed')
@section('content')
<section class="bg-gray-50 py-20 min-h-[60vh]">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-3">Order Confirmed!</h1>
            <p class="text-gray-500 mb-8">Thank you for your purchase. Your order has been received.</p>

            @if($payment && isset($payment['instructions']))
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-6 text-left mb-8">
                    <h2 class="font-semibold text-indigo-900 mb-2">{{ $payment['instructions']['title'] ?? 'Payment Instructions' }}</h2>
                    <ul class="space-y-2">
                        @foreach($payment['instructions']['lines'] ?? [] as $line)
                            <li class="flex items-start gap-2 text-sm text-indigo-800">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $line }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-xs text-indigo-500 mt-3">Your downloads unlock as soon as your payment is confirmed.</p>
                </div>
            @endif

            <div class="bg-gray-50 rounded-xl p-6 text-left mb-8">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Order Number</span>
                        <p class="font-mono font-bold text-gray-900">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Total</span>
                        <p class="font-bold text-gray-900 text-lg">${{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Status</span>
                        <p class="font-medium text-emerald-600 capitalize">{{ $order->status }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Date</span>
                        <p class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    @if($order->invoice)
                        <div>
                            <span class="text-gray-500">Invoice</span>
                            <p class="font-mono font-bold text-gray-900">{{ $order->invoice->invoice_number }}</p>
                        </div>
                    @endif
                </div>

                @if($order->items->count())
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <span class="text-gray-500 text-sm">Items</span>
                        @foreach($order->items as $item)
                            <div class="flex justify-between mt-2 text-sm">
                                <span class="text-gray-900">{{ $item->name }} x{{ $item->quantity }}</span>
                                <span class="font-medium">${{ number_format($item->total_price, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="px-6 py-3 border border-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-100 transition-colors">
                    Back to Home
                </a>
                <a href="{{ route('products.index') }}" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
