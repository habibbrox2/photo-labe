@extends('layouts.app')

@section('seo')
    <x-seo-meta
        title="{{ $product->seo_title ?? $product->title }}"
        description="{{ $product->seo_description ?? $product->short_description }}"
        image="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : '' }}"
        type="product"
        :schema="[
            'type' => 'Product',
            'name' => $product->title,
            'description' => $product->short_description,
            'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : '',
            'price' => $product->price,
            'priceCurrency' => 'USD',
            'brand' => ['@type' => 'Brand', 'name' => config('app.name')],
        ]"
    />
@endsection

@section('content')
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('products.index') }}" class="text-indigo-600 text-sm hover:text-indigo-700 mb-6 inline-block">← Back to Products</a>
        <div class="grid lg:grid-cols-2 gap-12">
            <div>
                @if($product->featured_image)
                    <div class="rounded-2xl overflow-hidden bg-gray-100 aspect-square">
                        <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>
            <div>
                <span class="text-sm font-medium text-purple-600">{{ $product->category->name ?? '' }}</span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 mb-4">{{ $product->title }}</h1>
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                    @if($product->sale_price)
                        <span class="text-xl text-gray-400 line-through">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="px-2 py-0.5 bg-red-100 text-red-600 text-sm font-medium rounded-full">Sale</span>
                    @endif
                </div>
                @if($product->short_description)
                    <p class="text-gray-600 leading-relaxed mb-6">{{ $product->short_description }}</p>
                @endif
                @if($product->description)
                    <div class="prose prose-sm max-w-none mb-6">{!! $product->description !!}</div>
                @endif
                <div class="flex gap-3">
                    <form action="{{ route('cart.index') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors">
                            Add to Cart
                        </button>
                    </form>
                </div>
                @if($product->compatibility)
                    <div class="mt-6 p-4 bg-gray-100 rounded-xl">
                        <div class="text-sm font-semibold text-gray-700 mb-1">Compatibility</div>
                        <div class="text-sm text-gray-500">{{ $product->compatibility }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
