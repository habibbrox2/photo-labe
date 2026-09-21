@extends('layouts.app')
@section('title', 'Our Services')
@section('content')
@php
$showPrice = \App\Models\Setting::flag('services_show_price', true);
@endphp

{{-- Hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Services</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-14 lg:gap-10 items-center">
            {{-- Copy --}}
            <div class="lg:col-span-7 max-w-2xl">
                <span class="eyebrow">Photo Editing Studio</span>
                <h1 class="mt-5 text-4xl md:text-5xl lg:text-[3.4rem] font-bold text-gray-900 tracking-tight leading-[1.08]">
                    Every image, finished<br class="hidden sm:block"> to a <em class="italic text-accent-600">professional&nbsp;standard.</em>
                </h1>
                <p class="mt-6 text-lg text-gray-500 leading-relaxed">
                    Retouching, background removal, color grading and creative design — handled by specialists with fast turnaround and unlimited revisions on every order.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">
                        Get a free quote
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </a>
                    <a href="#services" class="btn btn-lg btn-secondary">
                        Browse services
                        <x-icon name="chevron-down" class="w-4 h-4" />
                    </a>
                </div>
                <ul class="mt-9 flex flex-wrap gap-x-7 gap-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3 h-3 text-accent-700" /></span>
                        12-hour express delivery
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3 h-3 text-accent-700" /></span>
                        Free revisions
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3 h-3 text-accent-700" /></span>
                        Commercial-grade output
                    </li>
                </ul>
            </div>

            {{-- Sample image collage --}}
            <div class="lg:col-span-5">
                @php $heroShots = $services->take(3); @endphp
                @if($heroShots->count() >= 2)
                <div class="relative max-w-md mx-auto lg:max-w-none">
                    <div class="absolute -inset-6 bg-gradient-to-br from-accent-200/40 via-transparent to-transparent blur-2xl rounded-full" aria-hidden="true"></div>
                    <div class="relative grid grid-cols-2 gap-4">
                        @foreach($heroShots as $i => $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="group block overflow-hidden rounded-2xl border border-surface-200 bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 {{ $i % 2 === 0 ? 'mt-6' : '' }}">
                            <div class="aspect-[4/5] overflow-hidden">
                                @if($service->featured_image)
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $service->featured_image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-gray-100 to-surface-200 flex items-center justify-center">
                                    <x-icon name="image" class="w-10 h-10 text-gray-300" />
                                </div>
                                @endif
                            </div>
                            <div class="px-4 py-3">
                                <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $service->category->name ?? 'Studio' }}</div>
                                <div class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors leading-snug">{{ $service->title }}</div>
                            </div>
                        </a>
                        @endforeach
                        <div class="absolute -bottom-5 -left-5 rounded-2xl bg-surface-900 text-white px-5 py-4 shadow-xl ring-4 ring-white">
                            <div class="text-2xl font-bold text-accent-400 leading-none">4.9<span class="text-sm text-white/60 font-medium">/5</span></div>
                            <div class="mt-1 text-[11px] text-white/70 font-medium uppercase tracking-wide">Client rating</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Service grid --}}
<section id="services" class="py-20 lg:py-24" x-data="{ activeCategory: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Category tabs --}}
        <div class="flex flex-wrap items-center justify-center gap-2.5 mb-14">
            <button @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900'"
                class="px-5 py-2.5 rounded-full text-sm font-semibold border transition-all duration-200 cursor-pointer">
                All Services
            </button>
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->slug }}'"
                :class="activeCategory === '{{ $category->slug }}' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900'"
                class="px-5 py-2.5 rounded-full text-sm font-semibold border transition-all duration-200 cursor-pointer">
                {{ $category->name }}
                <span class="ml-1.5 text-xs opacity-60">{{ $category->services_count }}</span>
            </button>
            @endforeach
        </div>

        {{-- Cards --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @forelse($services as $service)
            <div x-show="activeCategory === 'all' || activeCategory === '{{ $service->category->slug ?? '' }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-[0.98]"
                x-transition:enter-end="opacity-100 scale-100">
                <a href="{{ route('services.show', $service->slug) }}" class="group block h-full">
                    <div class="relative rounded-2xl overflow-hidden border border-surface-200 bg-surface-100 aspect-[16/10]">
                        @if($service->featured_image)
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $service->featured_image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>
                        @if($service->category)
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur text-[11px] font-semibold text-gray-700 shadow-sm">{{ $service->category->name }}</span>
                        @endif
                    </div>
                    <div class="pt-5">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-bold text-gray-900 tracking-tight group-hover:text-primary-600 transition-colors leading-snug">{{ $service->title }}</h3>
                            <span class="mt-0.5 w-7 h-7 shrink-0 rounded-full border border-surface-200 flex items-center justify-center text-gray-400 group-hover:bg-gray-900 group-hover:border-gray-900 group-hover:text-white transition-all duration-200">
                                <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                            </span>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $service->short_description }}</p>
                        <div class="mt-4 pt-4 border-t border-surface-200 flex items-center justify-between">
                            @if($showPrice && $service->starting_price)
                            <span class="text-sm font-bold text-gray-900">From <span class="text-accent-600">${{ number_format($service->starting_price, 2) }}</span></span>
                            @else
                            <span class="text-sm text-gray-400">Custom pricing</span>
                            @endif
                            @if($service->delivery_time)
                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                <x-icon name="clock" class="w-3.5 h-3.5 text-gray-400" />
                                {{ $service->delivery_time }}
                            </span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-semibold text-gray-600">No services found</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16">{{ $services->links() }}</div>
    </div>
</section>

{{-- Bottom CTA --}}
<section class="pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl overflow-hidden bg-surface-900 px-8 py-14 md:px-16 md:py-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative">
                <span class="eyebrow !bg-accent-500/15 !text-accent-300">Not sure which service fits?</span>
                <h2 class="mt-4 text-3xl md:text-4xl font-bold text-white tracking-tight">Tell us about your images — we'll recommend the right edit.</h2>
                <p class="mt-3 text-white/70 max-w-xl mx-auto">Send a sample and get a free, no-obligation quote from a real retoucher within a few hours.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">
                        Request a free quote
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">
                        Talk to us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
