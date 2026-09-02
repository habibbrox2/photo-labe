@extends('layouts.app')
@section('title', 'Shopping Cart')
@section('content')
<section class="bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center text-gray-400">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <p class="text-lg font-semibold text-gray-600 mb-2">Your cart is empty</p>
            <a href="{{ route('products.index') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">Browse Products →</a>
        </div>
    </div>
</section>
@endsection
