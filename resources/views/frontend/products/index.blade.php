@extends('layouts.app')
@section('title', 'Digital Products')
@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Digital Products</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Premium presets, actions, brushes, and templates for photographers and designers.</p>
    </div>
</section>
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="group card-hover surface-card overflow-hidden">
                <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 overflow-hidden">
                    @if($product->featured_image)
                    <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                </div>
                <div class="p-5">
                    <div class="text-xs font-bold text-accent-600 uppercase tracking-wider mb-2">{{ $product->category->name ?? '' }}</div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-accent-600 transition-colors mb-2">{{ $product->title }}</h3>
                    <div class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <p>No products found.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-12">{{ $products->links() }}</div>
    </div>
</section>
@endsection