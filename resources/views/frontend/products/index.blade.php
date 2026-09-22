@extends('layouts.app')
@section('title', 'Digital Products')
@php
$showPrice = \App\Models\Setting::flag('products_show_price', true);
@endphp
@section('content')

{{-- Hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Products</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-10 items-end">
            <div class="lg:col-span-8">
                <span class="eyebrow">Digital Products</span>
                <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">
                    Pro tools, <em class="italic text-accent-600">instant download</em>
                </h1>
                <p class="mt-5 text-lg text-gray-500 max-w-xl">Presets, actions and LUTs crafted by the same team behind our commercial retouching.</p>
            </div>
            <div class="lg:col-span-4 lg:text-right">
                <div class="inline-flex items-center gap-3">
                    <span class="text-4xl font-extrabold text-gray-900 leading-none">{{ $products->total() }}</span>
                    <span class="text-sm text-gray-400 leading-tight">products<br>in the shop</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Filter bar --}}
<section class="pb-4 bg-white border-b border-surface-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col md:flex-row md:items-center gap-3 py-4">
            <div class="relative flex-1">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search presets, actions, LUTs…" aria-label="Search products"
                    class="w-full rounded-xl border border-surface-200 bg-surface-50 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:border-accent-500 focus:ring-2 focus:ring-accent-500/20 focus:outline-none transition">
            </div>
            <select name="category" aria-label="Filter by category"
                class="rounded-xl border border-surface-200 bg-surface-50 px-4 py-2.5 text-sm text-gray-900 focus:border-accent-500 focus:outline-none transition">
                <option value="">All categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }} ({{ $category->products_count }})</option>
                @endforeach
            </select>
            <select name="sort" aria-label="Sort products"
                class="rounded-xl border border-surface-200 bg-surface-50 px-4 py-2.5 text-sm text-gray-900 focus:border-accent-500 focus:outline-none transition">
                <option value="" @selected(!request('sort'))>Newest</option>
                <option value="popular" @selected(request('sort') === 'popular')>Most popular</option>
                <option value="price_low" @selected(request('sort') === 'price_low')>Price: Low to High</option>
                <option value="price_high" @selected(request('sort') === 'price_high')>Price: High to Low</option>
            </select>
            <button type="submit" class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 transition">Filter</button>
            @if(request('q') || request('category') || request('sort'))
            <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 underline underline-offset-4">Reset</a>
            @endif
        </form>
    </div>
</section>

{{-- Product grid --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($products->isEmpty())
        <div class="text-center py-20">
            <x-icon name="package" class="w-12 h-12 text-gray-300 mx-auto" />
            <h3 class="mt-4 text-lg font-bold text-gray-900">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">Try a different search term or category.</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-sm font-semibold text-accent-600 hover:text-accent-700">Clear filters</a>
        </div>
        @else
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="group block">
                <div class="relative aspect-square rounded-2xl overflow-hidden border border-surface-200/80 bg-surface-100">
                    @if($product->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-[1.06] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <x-icon name="package" class="w-14 h-14 text-gray-300" />
                    </div>
                    @endif
                    @if($product->on_sale)
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-accent-500 text-gray-900 text-[11px] font-bold shadow-sm">Sale</span>
                    @endif
                    <span class="absolute inset-0 bg-gray-900/0 group-hover:bg-gray-900/10 transition-colors duration-300" aria-hidden="true"></span>
                </div>
                <div class="pt-4 px-0.5">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $product->category->name ?? 'Product' }}</div>
                    <h3 class="mt-0.5 text-sm font-bold text-gray-900 leading-snug group-hover:text-primary-600 transition-colors">{{ $product->title }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        @if($showPrice)
                        <span class="text-base font-extrabold text-gray-900">{{ money($product->effective_price) }}</span>
                        @if($product->on_sale)
                        <span class="text-xs text-gray-400 line-through">{{ money($product->price) }}</span>
                        @endif
                        @else
                        <span class="text-sm text-gray-400">Price on request</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <x-icon name="package" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
                <p class="text-lg font-semibold text-gray-600">No products yet — check back soon.</p>
            </div>
            @endforelse
        </div>
        @endif

        <div class="mt-16">{{ $products->links() }}</div>
    </div>
</section>

{{-- Closing CTA --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-14 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Can't find the tool you need? <em class="italic text-accent-400">We'll edit for you.</em></h2>
                <p class="mt-4 text-white/70 text-lg">Skip the presets — send your images and get professional edits, delivered fast.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get Your Free Quote <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <a href="{{ route('portfolio.index') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">See Our Work</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
