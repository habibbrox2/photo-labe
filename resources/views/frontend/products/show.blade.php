@extends('layouts.app')

@section('seo')
    <x-seo-meta
        :title="$product->seo_title ?? $product->title"
        :description="$product->seo_description ?? $product->short_description"
        :image="$product->featured_image ? asset('storage/' . $product->featured_image) : null"
        type="product"
        :schema="[
            'type' => 'Product',
            'name' => $product->title,
            'description' => $product->short_description,
            'image' => $product->featured_image ? asset('storage/' . $product->featured_image) : '',
            'price' => $product->effective_price,
            'priceCurrency' => 'USD',
            'brand' => ['@type' => 'Brand', 'name' => config('app.name')],
        ]"
    />
@endsection

@section('content')

{{-- Product hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-14 items-center">
            {{-- Visual: interactive before/after when available, else featured image --}}
            <div class="lg:col-span-6">
                @if($heroBeforeAfter)
                <x-before-after
                    before="{{ asset('storage/' . $heroBeforeAfter->before_image) }}"
                    after="{{ asset('storage/' . $heroBeforeAfter->after_image) }}"
                />
                @elseif($product->featured_image)
                <div class="overflow-hidden rounded-3xl border border-surface-200/80 bg-white shadow-[0_30px_60px_-30px_rgba(28,25,23,0.35)]">
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full aspect-square object-cover">
                </div>
                @endif
            </div>

            {{-- Purchase panel --}}
            <div class="lg:col-span-6">
                <nav aria-label="Breadcrumb">
                    <ol class="flex items-center gap-1.5 text-sm text-gray-500 mb-6">
                        <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                        <li aria-hidden="true" class="text-gray-300">/</li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-primary-600 transition-colors">Products</a></li>
                        <li aria-hidden="true" class="text-gray-300">/</li>
                        <li aria-current="page" class="text-gray-700 font-medium">{{ $product->title }}</li>
                    </ol>
                </nav>

                <div class="flex flex-wrap items-center gap-2.5">
                    @if($product->category)
                    <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-accent-700 bg-accent-100 rounded-full px-3.5 py-1.5">{{ $product->category->name }}</span>
                    @endif
                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 bg-white border border-surface-200 rounded-full px-3.5 py-1.5">
                        <x-icon name="download" class="w-3.5 h-3.5 text-gray-400" />
                        Instant download
                    </span>
                </div>

                <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.1]">{{ $product->title }}</h1>

                <div class="mt-6 flex items-center gap-3">
                    <span class="text-4xl font-extrabold text-gray-900">${{ number_format($product->effective_price, 2) }}</span>
                    @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="text-xl text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                    <span class="px-2.5 py-1 bg-accent-500 text-gray-900 text-xs font-bold rounded-full">Sale</span>
                    @endif
                </div>

                @if($product->short_description)
                <p class="mt-5 text-lg text-gray-500 leading-relaxed">{{ $product->short_description }}</p>
                @endif

                @if(is_array($product->features) && count($product->features))
                <ul class="mt-7 space-y-3">
                    @foreach($product->features as $feature)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0">
                            <x-icon name="check" class="w-3 h-3 text-accent-700" />
                        </span>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="mt-9">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-lg btn-gradient w-full sm:w-auto">
                        <x-icon name="cart" class="w-5 h-5" />
                        Add to Cart — ${{ number_format($product->effective_price, 2) }}
                    </button>
                </form>
                <p class="mt-3 text-xs text-gray-400 flex items-center gap-1.5">
                    <x-icon name="shield" class="w-3.5 h-3.5" />
                    Secure checkout · license for commercial &amp; personal use
                </p>

                @if($product->compatibility)
                <div class="mt-8 p-5 rounded-2xl bg-white border border-surface-200">
                    <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2 flex items-center gap-2">
                        <x-icon name="settings" class="w-4 h-4" /> Compatibility
                    </div>
                    <div class="text-sm font-semibold text-gray-700">{{ $product->compatibility }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Details --}}
@if($product->description)
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-14">
            <div class="lg:col-span-3">
                <span class="eyebrow">Details</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">What you get</h2>
            </div>
            <div class="lg:col-span-8 prose prose-lg max-w-none prose-headings:tracking-tight prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary-600 prose-strong:text-gray-900 prose-li:text-gray-600">
                {!! render_shortcodes($product->description) !!}
            </div>
        </div>
    </div>
</section>
@endif

{{-- Before / After strip --}}
@if($beforeAfterItems->count())
<section class="py-16 lg:py-20 bg-surface-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="eyebrow">Before &amp; After</span>
            <h2 class="mt-5 text-4xl font-extrabold tracking-tight text-gray-900">See the <em class="italic text-accent-600">difference</em></h2>
            <p class="mt-5 text-lg text-gray-500">Drag the sliders to compare the transformation — the exact quality our tools are built on.</p>
        </div>
        <div class="mt-14 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($beforeAfterItems as $item)
            <x-before-after
                before="{{ asset('storage/' . $item->before_image) }}"
                after="{{ asset('storage/' . $item->after_image) }}"
                title="{{ $item->title }}"
            />
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Related products --}}
@if($relatedProducts->count())
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
            <div>
                <span class="eyebrow">Keep browsing</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">You might also like</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-md btn-secondary">All Products <x-icon name="arrow-right" class="w-4 h-4" /></a>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
            @foreach($relatedProducts as $related)
            <a href="{{ route('products.show', $related->slug) }}" class="group block">
                <div class="relative aspect-square rounded-2xl overflow-hidden border border-surface-200/80 bg-surface-100">
                    @if($related->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-[1.06] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center"><x-icon name="package" class="w-12 h-12 text-gray-300" /></div>
                    @endif
                    @if($related->sale_price && $related->sale_price < $related->price)
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-accent-500 text-gray-900 text-[11px] font-bold shadow-sm">Sale</span>
                    @endif
                </div>
                <div class="pt-4 px-0.5">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $related->category->name ?? 'Product' }}</div>
                    <h3 class="mt-0.5 text-sm font-bold text-gray-900 leading-snug group-hover:text-primary-600 transition-colors">{{ $related->title }}</h3>
                    <div class="mt-2 text-base font-extrabold text-gray-900">${{ number_format($related->effective_price, 2) }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
