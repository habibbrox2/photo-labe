@extends('layouts.app')
@section('title', 'Digital Products')
@section('content')
<section class="bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Digital Products</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Premium presets, actions, brushes, and templates for photographers and designers.</p>
    </div>
</section>
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all border border-gray-100">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 overflow-hidden">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="text-xs font-medium text-purple-600 mb-1">{{ $product->category->name ?? '' }}</div>
                        <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors mb-2">{{ $product->title }}</h3>
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
