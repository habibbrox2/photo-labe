@extends('layouts.app')
@section('title', 'Shopping Cart')
@section('content')
<section class="bg-gray-50 py-12 min-h-[60vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        @if($cart && $cart->items->count())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Cart Items --}}
                <div class="divide-y divide-gray-100">
                    @foreach($cart->items as $item)
                        <div class="p-6 flex items-center gap-6">
                            {{-- Product Image --}}
                            <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl overflow-hidden shrink-0">
                                @if($item->product->featured_image)
                                    <img src="{{ asset('storage/' . $item->product->featured_image) }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors">{{ $item->product->title }}</a>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $item->product->category->name ?? '' }}</p>
                                <p class="text-sm font-medium text-gray-900 mt-1">${{ number_format($item->price, 2) }}</p>
                            </div>

                            {{-- Quantity --}}
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="quantity" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </form>

                            {{-- Subtotal --}}
                            <div class="text-right min-w-[80px]">
                                <p class="font-semibold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>

                            {{-- Remove --}}
                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Remove">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                {{-- Cart Summary --}}
                <div class="bg-gray-50 p-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">${{ number_format($cart->total, 2) }}</span>
                    </div>
                    @if($cart->discount > 0)
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-600">Discount</span>
                            <span class="font-semibold text-emerald-600">-${{ number_format($cart->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between mb-6 pt-4 border-t border-gray-200">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-lg font-bold text-gray-900">${{ number_format($cart->grand_total, 2) }}</span>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ route('products.index') }}" class="flex-1 text-center px-6 py-3 border border-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-100 transition-colors">
                            Continue Shopping
                        </a>
                        <a href="{{ route('checkout.show') }}" class="flex-1 text-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors">
                            Proceed to Checkout
                        </a>
                    </div>

                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-4 text-center">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-500 transition-colors">Clear Cart</button>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-6">Browse our products and add items to your cart.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
