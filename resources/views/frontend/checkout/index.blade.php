@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<section class="bg-gray-50 py-12 min-h-[60vh]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>

        <form method="POST" action="{{ route('checkout.process') }}">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Billing Info --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
                        <h2 class="text-lg font-semibold text-gray-900">Billing Information</h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Name *</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required
                                    class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-300' : 'border-gray-200' }} focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email *</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required
                                    class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-300' : 'border-gray-200' }} focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">{{ old('address') }}</textarea>
                        </div>
                    </div>

                    {{-- Payment Method --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h2>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input type="radio" name="payment_method" value="manual" checked class="text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">Manual Payment</p>
                                    <p class="text-xs text-gray-500">Pay via bank transfer or other arrangement</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                        <div class="space-y-3 mb-4">
                            @foreach($cart->items as $item)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate">{{ $item->product->title }}</p>
                                        <p class="text-gray-500">x{{ $item->quantity }}</p>
                                    </div>
                                    <span class="font-medium text-gray-900 ml-4">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="font-medium">${{ number_format($cart->total, 2) }}</span>
                            </div>
                            @if($cart->discount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Discount</span>
                                    <span class="font-medium text-emerald-600">-${{ number_format($cart->discount, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-100">
                                <span>Total</span>
                                <span>${{ number_format($cart->grand_total, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-6 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
