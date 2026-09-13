@extends('layouts.app')
@section('title', 'Shopping Cart')
@section('content')

<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <nav aria-label="Breadcrumb" class="mb-8">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Cart</li>
            </ol>
        </nav>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Shopping cart</h1>
    </div>
</section>

<section class="py-12 lg:py-16 bg-white min-h-[55vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-flash class="mb-8" />

        @if($cart && $cart->items->count())
        <div class="grid lg:grid-cols-3 gap-10">
            {{-- Items --}}
            <div class="lg:col-span-2">
                <div class="rounded-3xl border border-surface-200 overflow-hidden bg-white">
                    <div class="divide-y divide-surface-200">
                        @foreach($cart->items as $item)
                        <div class="p-5 sm:p-6 flex items-center gap-5">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden border border-surface-200/80 bg-surface-100">
                                @if($item->product->featured_image)
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $item->product->featured_image) }}" alt="{{ $item->product->title }}" class="w-full h-full object-cover">
                                @else
                                <div class="w-full h-full flex items-center justify-center"><x-icon name="package" class="w-7 h-7 text-gray-300" /></div>
                                @endif
                            </a>

                            <div class="flex-1 min-w-0">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="font-bold text-gray-900 hover:text-primary-600 transition-colors leading-snug">{{ $item->product->title }}</a>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $item->product->category->name ?? 'Digital product' }}</p>
                                <p class="text-sm font-bold text-gray-900 mt-1.5">${{ number_format($item->price, 2) }}</p>
                            </div>

                            {{-- Quantity (auto-submit) --}}
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="quantity" data-auto-submit aria-label="Quantity for {{ $item->product->title }}"
                                    class="px-3 py-2 rounded-xl border border-surface-200 text-sm font-medium text-gray-700 focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 outline-none transition-all">
                                    @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </form>

                            {{-- Line total --}}
                            <div class="text-right min-w-[72px] hidden sm:block">
                                <p class="font-extrabold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>

                            {{-- Remove --}}
                            <form method="POST" action="{{ route('cart.remove', $item) }}" data-confirm="Remove {{ $item->product->title }} from your cart?" data-confirm-label="Remove">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-full border border-surface-200 flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-all" title="Remove item" aria-label="Remove {{ $item->product->title }} from cart">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('products.index') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-primary-600 transition-colors">
                    <x-icon name="arrow-left" class="w-4 h-4" />
                    Continue shopping
                </a>
            </div>

            {{-- Summary --}}
            <aside>
                <div class="lg:sticky lg:top-24 rounded-3xl border border-surface-200 bg-surface-50/60 p-7">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-6">Order summary</h2>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Subtotal</dt>
                            <dd class="font-semibold text-gray-900">${{ number_format($cart->total, 2) }}</dd>
                        </div>
                        @if($cart->discount > 0)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Discount</dt>
                            <dd class="font-semibold text-emerald-600">−${{ number_format($cart->discount, 2) }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between pt-4 mt-4 border-t border-surface-200 text-base">
                            <dt class="font-bold text-gray-900">Total</dt>
                            <dd class="font-extrabold text-gray-900">${{ number_format($cart->grand_total, 2) }}</dd>
                        </div>
                    </dl>
                    <a href="{{ route('checkout.show') }}" class="btn btn-lg btn-gradient w-full mt-6">Proceed to Checkout <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-4 text-center" data-confirm="Remove all items from your cart?" data-confirm-label="Clear cart">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-medium text-gray-400 hover:text-red-500 transition-colors">Clear cart</button>
                    </form>
                    <ul class="mt-6 pt-6 border-t border-surface-200 space-y-2.5 text-xs text-gray-500">
                        <li class="flex items-center gap-2"><x-icon name="check" class="w-3.5 h-3.5 text-accent-600" /> Instant download after payment</li>
                        <li class="flex items-center gap-2"><x-icon name="check" class="w-3.5 h-3.5 text-accent-600" /> License for commercial use</li>
                        <li class="flex items-center gap-2"><x-icon name="check" class="w-3.5 h-3.5 text-accent-600" /> Secure checkout</li>
                    </ul>
                </div>
            </aside>
        </div>
        @else
        <div class="max-w-lg mx-auto text-center py-16">
            <span class="mx-auto w-20 h-20 rounded-3xl bg-surface-100 border border-surface-200 flex items-center justify-center mb-6">
                <x-icon name="cart" class="w-10 h-10 text-gray-300" />
            </span>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Your cart is empty</h2>
            <p class="mt-2 text-gray-500">Browse our presets, actions and tools — or let us handle the editing for you.</p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('products.index') }}" class="btn btn-lg btn-primary">Browse Products</a>
                <a href="{{ route('services.index') }}" class="btn btn-lg btn-secondary">Explore Services</a>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
