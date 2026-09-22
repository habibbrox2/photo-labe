@extends('layouts.app')
@section('title', 'Checkout')
@section('content')

<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <nav aria-label="Breadcrumb" class="mb-8">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><a href="{{ route('cart.index') }}" class="hover:text-primary-600 transition-colors">Cart</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Checkout</li>
            </ol>
        </nav>
        <div class="flex flex-wrap items-center justify-between gap-6">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Checkout</h1>
            {{-- Progress stepper --}}
            <ol class="flex items-center gap-2 text-sm" aria-label="Checkout progress">
                <li class="flex items-center gap-2">
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1.5 text-gray-500 hover:text-primary-600 transition-colors font-medium">
                        <span class="w-6 h-6 rounded-full border border-surface-300 flex items-center justify-center"><x-icon name="check" class="w-3 h-3" /></span>
                        Cart
                    </a>
                </li>
                <li aria-hidden="true" class="text-gray-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-gray-900 font-bold">
                        <span class="w-6 h-6 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs">2</span>
                        Checkout
                    </span>
                </li>
                <li aria-hidden="true" class="text-gray-300">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                <li class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 text-gray-400 font-medium">
                        <span class="w-6 h-6 rounded-full border border-surface-300 flex items-center justify-center text-xs">3</span>
                        Confirmation
                    </span>
                </li>
            </ol>
        </div>
    </div>
</section>

<section class="py-12 lg:py-16 bg-white min-h-[55vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-flash class="mb-8" />

        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                {{-- Billing + payment --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="rounded-3xl border border-surface-200 bg-white p-7 md:p-8">
                        <h2 class="text-lg font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-accent-500 text-gray-900 text-xs font-extrabold flex items-center justify-center">1</span>
                            Billing Information
                        </h2>
                        <div class="mt-6 grid sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                    class="form-control-modern {{ $errors->has('name') ? '!border-red-300' : '' }}">
                                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                                    class="form-control-modern {{ $errors->has('email') ? '!border-red-300' : '' }}">
                                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone <span class="font-normal text-gray-400">(optional)</span></label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="form-control-modern">
                                @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Address <span class="font-normal text-gray-400">(optional)</span></label>
                                <textarea id="address" name="address" rows="2" class="form-control-modern resize-none">{{ old('address') }}</textarea>
                                @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-surface-200 bg-white p-7 md:p-8">
                        <h2 class="text-lg font-extrabold text-gray-900 tracking-tight flex items-center gap-2.5">
                            <span class="w-7 h-7 rounded-full bg-accent-500 text-gray-900 text-xs font-extrabold flex items-center justify-center">2</span>
                            Payment Method
                        </h2>
                        <div class="mt-6 space-y-3">
                            @foreach($gateways as $gateway)
                            <label class="flex items-start gap-4 p-5 rounded-2xl border border-surface-200 cursor-pointer transition-all has-[:checked]:border-accent-500 has-[:checked]:bg-accent-50/50 has-[:checked]:ring-1 has-[:checked]:ring-accent-500/30 hover:border-gray-300">
                                <input type="radio" name="payment_method" value="{{ $gateway }}" @checked($loop->first) class="mt-1 text-accent-600 focus:ring-accent-500 accent-accent-600">
                                <span class="min-w-0">
                                    <span class="block font-bold text-gray-900 text-sm capitalize">{{ str_replace('_', ' ', $gateway) }} Payment</span>
                                    <span class="block text-xs text-gray-500 mt-1 leading-relaxed">
                                        @if($gateway === 'manual')
                                        Pay via bank transfer / mobile banking — our team confirms your order manually
                                        @else
                                        Secure online payment — you'll be redirected to complete checkout
                                        @endif
                                    </span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('payment_method') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Order summary --}}
                <aside class="lg:col-span-1">
                    <div class="lg:sticky lg:top-24 rounded-3xl border border-surface-200 bg-surface-50/60 p-7">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-5">Order summary</h2>
                        <div class="space-y-3">
                            @foreach($cart->items as $item)
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-8 shrink-0 rounded-lg overflow-hidden bg-surface-200 border border-surface-200/60">
                                    @if($item->product->featured_image)
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $item->product->featured_image) }}" alt="" class="w-full h-full object-cover">
                                    @endif
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $item->product->title }}</p>
                                    <p class="text-xs text-gray-400">Qty {{ $item->quantity }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ money($item->price * $item->quantity) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <dl class="mt-5 pt-5 border-t border-surface-200 space-y-2.5 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Subtotal</dt>
                                <dd class="font-semibold text-gray-900">{{ money($cart->total) }}</dd>
                            </div>
                            @if($cart->discount > 0)
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Discount</dt>
                                <dd class="font-semibold text-emerald-600">−{{ money($cart->discount) }}</dd>
                            </div>
                            @endif
                            <div class="flex justify-between text-base pt-3 border-t border-surface-200">
                                <dt class="font-extrabold text-gray-900">Total</dt>
                                <dd class="font-extrabold text-gray-900">{{ money($cart->grand_total) }}</dd>
                            </div>
                        </dl>
                        <button type="submit" class="btn btn-lg btn-gradient w-full mt-6">Place Order <x-icon name="arrow-right" class="w-4 h-4" /></button>
                        <a href="{{ route('cart.index') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-gray-600 transition-colors w-full justify-center">
                            <x-icon name="arrow-left" class="w-3.5 h-3.5" /> Back to cart
                        </a>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</section>
@endsection
