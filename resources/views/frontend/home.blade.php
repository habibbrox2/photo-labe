@extends('layouts.app')

@section('seo')
    <x-seo-meta
        :title="$seoData['title'] ?? 'Professional Photo Editing & Creative Design Services'"
        :description="$seoData['description'] ?? 'Transform your images into professional, market-ready visuals with expert photo editing and creative design services.'"
        :keywords="$seoData['keywords'] ?? []"
        :schema="$seoData['schema'] ?? []"
        :breadcrumb="$seoData['breadcrumb'] ?? []"
    />
@endsection

@section('content')
{{-- 1. Hero Section - Immersive Gradient Mesh --}}
<section class="relative min-h-[90vh] flex items-center overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-surface-900 via-surface-950 to-primary-950"></div>
    <div class="absolute inset-0 gradient-mesh opacity-50"></div>
    
    {{-- Animated Orbs --}}
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary-500/20 rounded-full blur-[120px] animate-float"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-accent-500/20 rounded-full blur-[100px] animate-float" style="animation-delay: 1s;"></div>
    
    {{-- Noise Texture --}}
    <div class="absolute inset-0 noise-overlay"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Left Content --}}
            <div class="text-left" x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 100)">
                <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="inline-flex items-center gap-2 px-4 py-2 glass-dark rounded-full text-sm text-white/80 mb-8">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        Trusted by 500+ businesses worldwide
                    </div>
                </div>
                
                <div x-show="shown" x-transition:enter="transition ease-out duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-[1.1] mb-6 tracking-tight">
                        Professional
                        <span class="gradient-text">Photo Editing</span>
                        & Creative Design
                    </h1>
                </div>
                
                <div x-show="shown" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    <p class="text-xl text-white/60 max-w-xl mb-10 leading-relaxed">
                        Transform your images into professional, market-ready visuals. Expert retouching, background removal, color correction, and creative design.
                    </p>
                </div>
                
                <div x-show="shown" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('quote.create') }}" class="btn-modern group inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-2xl hover:from-primary-700 hover:to-accent-700 transition-all shadow-xl shadow-primary-500/25 hover:shadow-2xl hover:shadow-primary-500/30 hover:-translate-y-1 text-lg">
                        Get a Free Quote
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 glass-dark text-white font-semibold rounded-2xl hover:bg-white/10 transition-all text-lg">
                        View Our Work
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Right - Before/After Preview --}}
            <div x-data="{ shown: false }" x-init="setTimeout(() => shown = true, 400)">
                <div x-show="shown" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="relative">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-black/30 aspect-[4/3]">
                        <img src="{{ asset('storage/demo/hero/main.jpg') }}" alt="Photo Editing" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                    
                    {{-- Floating Stats Card --}}
                    <div class="absolute -bottom-6 -left-6 glass-dark rounded-2xl p-4 animate-float">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <div class="text-white font-bold text-lg">10K+</div>
                                <div class="text-white/60 text-sm">Projects Done</div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Floating Rating Card --}}
                    <div class="absolute -top-4 -right-4 glass-dark rounded-2xl p-4 animate-float" style="animation-delay: 0.5s;">
                        <div class="flex items-center gap-2">
                            <div class="flex -space-x-1">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-white font-semibold">4.9/5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 2. Trust Logos / Stats --}}
<section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-sm font-medium text-gray-400 uppercase tracking-widest">Trusted by leading brands worldwide</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center justify-items-center opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
            @php
                $brands = ['FashionCo', 'GemHouse', 'StyleBrand', 'PrimeRealty'];
            @endphp
            @foreach($brands as $brand)
                <div class="text-2xl font-bold text-gray-400">{{ $brand }}</div>
            @endforeach
        </div>
    </div>
</section>

{{-- 3. Trust Statistics - Bento Grid --}}
<section class="py-20 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bento-grid">
            {{-- Main Stat --}}
            <div class="col-span-2 row-span-2 bg-gradient-to-br from-primary-600 to-accent-600 rounded-3xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="text-6xl md:text-7xl font-bold mb-2">500+</div>
                    <div class="text-xl text-white/80">Happy Clients Worldwide</div>
                    <div class="mt-8 flex items-center gap-2">
                        <div class="flex -space-x-2">
                            @for($i = 0; $i < 4; $i++)
                                <div class="w-8 h-8 rounded-full bg-white/20 border-2 border-white/30"></div>
                            @endfor
                        </div>
                        <span class="text-sm text-white/60">+more join daily</span>
                    </div>
                </div>
            </div>
            
            {{-- Small Stats --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">24h</div>
                <div class="text-sm text-gray-500">Turnaround</div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-primary-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">99%</div>
                <div class="text-sm text-gray-500">Satisfaction</div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">10K+</div>
                <div class="text-sm text-gray-500">Projects Done</div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-rose-100 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">24/7</div>
                <div class="text-sm text-gray-500">Support</div>
            </div>
        </div>
    </div>
</section>

{{-- 4. Featured Services --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    Our Services
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Professional Editing Services</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">From basic retouching to complex creative projects, we deliver exceptional quality every time.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredServices as $index => $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group card-hover bg-white rounded-3xl overflow-hidden border border-gray-100" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        @if($service->featured_image)
                            <img src="{{ asset('storage/' . $service->featured_image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-white/90 backdrop-blur-sm text-primary-600 text-sm font-semibold rounded-full">
                                Learn More
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-semibold text-primary-600 uppercase tracking-wider mb-2">{{ $service->category->name ?? 'Service' }}</div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2">{{ $service->title }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $service->short_description }}</p>
                        @if($service->starting_price)
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">From ${{ number_format($service->starting_price, 2) }}</span>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        @endif
                    </div>
                </a>
            @empty
                @foreach(range(1, 6) as $i)
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 animate-pulse">
                        <div class="aspect-[16/10] bg-gray-200"></div>
                        <div class="p-6">
                            <div class="h-3 bg-gray-200 rounded w-1/3 mb-3"></div>
                            <div class="h-5 bg-gray-200 rounded w-2/3 mb-2"></div>
                            <div class="h-3 bg-gray-100 rounded w-full"></div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-2xl hover:bg-gray-800 transition-all hover:-translate-y-0.5 shadow-lg">
                View All Services
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 5. Before / After Interactive --}}
<section class="py-24 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Before & After
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">See the Difference</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Drag the slider to compare our editing work. The quality speaks for itself.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($beforeAfter as $index => $item)
                <div x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" style="transition-delay: {{ $index * 100 }}ms">
                    <x-before-after
                        before="{{ asset('storage/' . $item->before_image) }}"
                        after="{{ asset('storage/' . $item->after_image) }}"
                        title="{{ $item->title }}"
                    />
                </div>
            @empty
                @foreach(range(1, 4) as $i)
                    <div class="rounded-3xl bg-gray-200 aspect-square animate-pulse"></div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('before-after') }}" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                View All Examples
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 6. Featured Portfolio --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Portfolio
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Our Recent Work</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Browse through our latest projects showcasing our expertise across various industries.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredPortfolio as $index => $project)
                <a href="{{ route('portfolio.show', $project->slug) }}" class="group card-hover relative rounded-3xl overflow-hidden aspect-[4/3] bg-gray-100" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    @if($project->featured_image)
                        <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-100 to-accent-100">
                            <svg class="w-20 h-20 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-8 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-500">
                        <div class="text-xs font-semibold text-primary-300 uppercase tracking-wider mb-1">{{ $project->category->name ?? 'Project' }}</div>
                        <h3 class="text-xl font-bold text-white mb-1">{{ $project->title }}</h3>
                        @if($project->client)
                            <p class="text-sm text-white/70">Client: {{ $project->client }}</p>
                        @endif
                    </div>
                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </div>
                    </div>
                </a>
            @empty
                @foreach(range(1, 6) as $i)
                    <div class="rounded-3xl bg-gray-200 aspect-[4/3] animate-pulse"></div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-2xl hover:bg-gray-800 transition-all hover:-translate-y-0.5 shadow-lg">
                View Full Portfolio
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 7. Digital Products --}}
@if($featuredProducts->count())
<section class="py-24 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-accent-50 text-accent-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    Digital Products
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Shop Premium Tools</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Professional presets, actions, brushes, and templates to elevate your workflow.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $index => $product)
                <a href="{{ route('products.show', $product->slug) }}" class="group card-hover bg-white rounded-3xl overflow-hidden border border-gray-100" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="aspect-square overflow-hidden relative bg-gradient-to-br from-accent-100 to-primary-100">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-accent-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-5 h-5 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="text-xs font-semibold text-accent-600 uppercase tracking-wider mb-2">{{ $product->category->name ?? 'Product' }}</div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-accent-600 transition-colors mb-3">{{ $product->title }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @if($product->sale_price)
                                <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-2xl hover:bg-gray-800 transition-all hover:-translate-y-0.5 shadow-lg">
                Browse All Products
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- 8. Why Choose Us --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Why Choose Us
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">What Sets Us Apart</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">We combine expertise, technology, and dedication to deliver exceptional results.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $features = [
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Quality Guarantee', 'desc' => 'Every project undergoes strict quality control before delivery.', 'color' => 'emerald'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Fast Turnaround', 'desc' => 'Most projects delivered within 24 hours. Rush service available.', 'color' => 'primary'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Expert Team', 'desc' => 'Professional designers with 10+ years of industry experience.', 'color' => 'accent'],
                    ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Free Revisions', 'desc' => 'Unlimited revisions until you are 100% satisfied with the result.', 'color' => 'amber'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Secure & Private', 'desc' => 'Your files are kept confidential with bank-level security.', 'color' => 'rose'],
                    ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'title' => 'Flexible Pricing', 'desc' => 'Competitive pricing with volume discounts for ongoing projects.', 'color' => 'primary'],
                ];
            @endphp

            @foreach($features as $index => $feature)
                <div class="group card-hover bg-white rounded-3xl p-8 border border-gray-100" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="w-14 h-14 bg-{{ $feature['color'] }}-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-{{ $feature['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 9. Work Process --}}
<section class="py-24 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    How It Works
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Simple 4-Step Process</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Get started in minutes with our streamlined workflow.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            @php
                $steps = [
                    ['num' => '01', 'title' => 'Submit Request', 'desc' => 'Fill out our quote form with your requirements and upload reference files.', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['num' => '02', 'title' => 'Get a Quote', 'desc' => 'We review your request and provide a detailed quote within 24 hours.', 'icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['num' => '03', 'title' => 'We Edit', 'desc' => 'Our expert team works on your project with precision and attention to detail.', 'icon' => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z'],
                    ['num' => '04', 'title' => 'Delivery', 'desc' => 'Receive your professionally edited files. Request revisions if needed.', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4'],
                ];
            @endphp

            @foreach($steps as $index => $step)
                <div class="text-center relative" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 150 }}ms">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-600 to-accent-600 rounded-3xl flex items-center justify-center text-white mx-auto mb-6 shadow-xl shadow-primary-500/25 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/></svg>
                    </div>
                    <div class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-2">Step {{ $step['num'] }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $step['title'] }}</h3>
                    <p class="text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    
                    @if(!$loop->last)
                        <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-gradient-to-r from-primary-200 to-transparent"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 10. Testimonials --}}
@if($testimonials->count())
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Testimonials
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">What Our Clients Say</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Don't just take our word for it. Here's what our clients have to say.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $index => $testimonial)
                <div class="group card-hover bg-white rounded-3xl p-8 border border-gray-100" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    @if($testimonial->rating)
                        <div class="flex gap-1 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    @endif
                    
                    <p class="text-gray-600 leading-relaxed mb-6 text-lg">"{{ $testimonial->content }}"</p>
                    
                    <div class="flex items-center gap-4">
                        @if($testimonial->avatar)
                            <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($testimonial->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-gray-900">{{ $testimonial->name }}</div>
                            @if($testimonial->title || $testimonial->company)
                                <div class="text-sm text-gray-500">{{ $testimonial->title }}{{ $testimonial->company ? ' at ' . $testimonial->company : '' }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 11. Blog Preview --}}
@if($latestPosts->count())
<section class="py-24 bg-surface-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Blog
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Latest Insights</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Tips, tutorials, and industry news from our editing experts.</p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($latestPosts as $index => $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group card-hover bg-white rounded-3xl overflow-hidden border border-gray-100" x-data="{ shown: false }" x-intersect="shown = true" x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="aspect-[16/10] overflow-hidden relative">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                                <svg class="w-16 h-16 text-primary-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            @if($post->category)
                                <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-3 py-1.5 rounded-full">{{ $post->category->name }}</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $post->published_at?->diffForHumans() ?? '' }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-3 line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-gray-500 line-clamp-2">{{ $post->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-2xl hover:bg-gray-800 transition-all hover:-translate-y-0.5 shadow-lg">
                Read More Articles
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- 12. FAQ --}}
<section class="py-24 bg-white" x-data="{ open: null }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" x-data="{ shown: false }" x-intersect="shown = true">
            <div x-show="shown" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    FAQ
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-lg">Everything you need to know about our services.</p>
            </div>
        </div>

        <div class="space-y-4">
            @php
                $faqs = [
                    ['q' => 'How long does a typical project take?', 'a' => 'Most standard projects are completed within 24-48 hours. Complex projects may take 3-5 business days. Rush delivery is available for urgent needs.'],
                    ['q' => 'What file formats do you accept?', 'a' => 'We accept all major image formats including JPG, PNG, TIFF, PSD, and RAW files. For digital products, we provide ZIP archives.'],
                    ['q' => 'Do you offer revisions?', 'a' => 'Yes, we offer free revisions until you are completely satisfied with the result. There is no limit on revisions for our standard and premium plans.'],
                    ['q' => 'How do I get a quote?', 'a' => 'Simply fill out our Get a Quote form with your requirements. Our team will review your request and provide a detailed quote within 24 hours.'],
                    ['q' => 'Is my data secure?', 'a' => 'Absolutely. All files are stored on encrypted servers and are never shared with third parties. We can sign NDAs for enterprise clients.'],
                ];
            @endphp

            @foreach($faqs as $i => $faq)
                <div class="bg-surface-50 rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300 {{ $i === 0 ? 'ring-2 ring-primary-500/20' : '' }}">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="w-full flex items-center justify-between p-6 text-left hover:bg-white transition-colors">
                        <span class="font-semibold text-gray-900 pr-4">{{ $faq['q'] }}</span>
                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0 transition-transform duration-300" :class="open === {{ $i }} ? 'rotate-180 bg-primary-600' : ''">
                            <svg class="w-4 h-4 transition-colors duration-300" :class="open === {{ $i }} ? 'text-white' : 'text-primary-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </button>
                    <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-6">
                        <p class="text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('faq') }}" class="inline-flex items-center gap-2 text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                View All FAQs
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endsection
