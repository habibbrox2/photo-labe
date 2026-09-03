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
{{-- ============ HERO ============ --}}
<section class="relative overflow-hidden border-b border-surface-200/70" x-data="{ loaded: false }" x-init="$nextTick(() => setTimeout(() => loaded = true, 80))">
    {{-- Ambient background --}}
    <div class="absolute inset-0 bg-gradient-to-b from-surface-50 via-white to-surface-100/60" aria-hidden="true"></div>
    <div class="absolute -top-32 -left-32 w-[480px] h-[480px] bg-accent-200/40 rounded-full blur-[130px]" aria-hidden="true"></div>
    <div class="absolute top-1/3 -right-40 w-[520px] h-[520px] bg-primary-200/30 rounded-full blur-[140px]" aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-20 lg:pt-24 lg:pb-28">
        <div class="grid lg:grid-cols-12 gap-14 items-center">
            {{-- Copy --}}
            <div class="lg:col-span-6">
                <div x-show="loaded" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="inline-flex items-center gap-2.5 bg-white border border-surface-200 shadow-sm pl-1.5 pr-4 py-1.5 rounded-full">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-white bg-accent-500 rounded-full px-2.5 py-1">
                            <x-icon name="star" class="w-3 h-3" /> 4.9
                        </span>
                        <span class="text-sm font-medium text-gray-600">Rated excellent by 500+ studios &amp; brands</span>
                    </div>
                </div>

                <h1 x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-75" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-7 text-[2.6rem] leading-[1.06] sm:text-6xl xl:text-[4.2rem] xl:leading-[1.02] font-extrabold tracking-tight text-gray-900">
                    Pixel-perfect photo editing for brands that <em class="italic text-accent-600">refuse to look average.</em>
                </h1>

                <p x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-150" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-6 text-lg text-gray-500 leading-relaxed max-w-xl">
                    Retouching, background removal, color grading and creative design — delivered by specialists in as little as 12 hours, with unlimited free revisions.
                </p>

                <div x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                    class="mt-9 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient shadow-xl shadow-accent-500/25">
                        Get a Free Quote
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="btn btn-lg btn-secondary">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                        View Our Work
                    </a>
                </div>

                <div x-show="loaded" x-transition:enter="transition ease-out duration-500 delay-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">24h</div>
                        <div class="mt-1 text-sm text-gray-500">avg. turnaround</div>
                    </div>
                    <div class="w-px h-10 bg-surface-200 hidden sm:block" aria-hidden="true"></div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">10k+</div>
                        <div class="mt-1 text-sm text-gray-500">projects delivered</div>
                    </div>
                    <div class="w-px h-10 bg-surface-200 hidden sm:block" aria-hidden="true"></div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">100%</div>
                        <div class="mt-1 text-sm text-gray-500">revision guarantee</div>
                    </div>
                </div>
            </div>

            {{-- Visual: layered editorial collage --}}
            <div class="lg:col-span-6" x-show="loaded" x-transition:enter="transition ease-out duration-700 delay-150" x-transition:enter-start="opacity-0 scale-[0.97]" x-transition:enter-end="opacity-100 scale-100">
                @php $collage = $featuredPortfolio->take(2); @endphp
                <div class="relative max-w-xl mx-auto">
                    {{-- Soft glow behind --}}
                    <div class="absolute -inset-8 bg-gradient-to-tr from-accent-200/50 via-transparent to-primary-200/40 blur-2xl rounded-full" aria-hidden="true"></div>

                    <div class="relative grid grid-cols-12 gap-4 items-start">
                        @foreach($collage as $i => $project)
                        <a href="{{ route('portfolio.show', $project->slug) }}" class="group {{ $i === 0 ? 'col-span-7' : 'col-span-5 mt-16' }} block">
                            <div class="overflow-hidden rounded-3xl border border-surface-200/80 bg-white shadow-[0_30px_60px_-30px_rgba(28,25,23,0.35)] group-hover:shadow-[0_36px_70px_-30px_rgba(28,25,23,0.45)] transition-shadow duration-500 {{ $i === 0 ? 'aspect-[4/5]' : 'aspect-[3/4]' }}">
                                @if($project->featured_image)
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-surface-100 to-surface-200 flex items-center justify-center">
                                    <x-icon name="image" class="w-14 h-14 text-gray-300" />
                                </div>
                                @endif
                            </div>
                            <div class="mt-3 flex items-center justify-between px-1">
                                <div>
                                    <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $project->category->name ?? 'Project' }}</div>
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $project->title }}</div>
                                </div>
                                <span class="w-7 h-7 shrink-0 rounded-full bg-white border border-surface-200 flex items-center justify-center text-gray-500 group-hover:bg-gray-900 group-hover:border-gray-900 group-hover:text-white transition-colors duration-200">
                                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    {{-- Floating chip: before/after CTA --}}
                    <a href="{{ route('before-after') }}" class="absolute -bottom-4 -right-2 sm:-right-6 group inline-flex items-center gap-3 bg-gray-900 text-white rounded-2xl pl-4 pr-5 py-3.5 shadow-2xl shadow-gray-900/30 hover:-translate-y-0.5 hover:shadow-gray-900/40 transition-all duration-300">
                        <span class="relative flex w-9 h-9 items-center justify-center rounded-full bg-accent-500 text-gray-900">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/></svg>
                        </span>
                        <span>
                            <span class="block text-[11px] uppercase tracking-wider text-white/60 font-medium">Drag to compare</span>
                            <span class="block text-sm font-bold">See our editing</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============ TRUST STRIP (real proof points) ============ --}}
@if($featuredPortfolio->count() || $testimonials->count())
<section class="border-b border-surface-200/70 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row md:items-center gap-8 md:justify-between">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-400 shrink-0">Trusted by teams at</p>
            @php
                $clients = collect($featuredPortfolio)->pluck('client')->filter()->unique()->take(4);
            @endphp
            @if($clients->count())
            <div class="flex flex-wrap items-center gap-x-10 gap-y-3 opacity-70">
                @foreach($clients as $client)
                <span class="text-lg font-bold text-gray-400/90 tracking-tight">{{ $client }}</span>
                @endforeach
                <span class="text-lg font-bold text-gray-400/90 tracking-tight">+ 500 more</span>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ============ FEATURED SERVICES ============ --}}
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="eyebrow">Our Services</span>
            <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Editing services that <em class="italic text-accent-600">sell your product</em></h2>
            <p class="mt-5 text-lg text-gray-500">From basic retouching to complex creative projects — every image is handled by a specialist who cares about the outcome.</p>
        </div>

        <div class="mt-16 grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
            @forelse($featuredServices as $service)
            <a href="{{ route('services.show', $service->slug) }}" class="group block">
                <div class="relative aspect-[16/10] overflow-hidden rounded-2xl border border-surface-200/80 bg-surface-100">
                    @if($service->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $service->featured_image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <x-icon name="image" class="w-12 h-12 text-gray-300" />
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/25 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" aria-hidden="true"></div>
                    @if($service->category)
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur text-[11px] font-semibold text-gray-700 shadow-sm">{{ $service->category->name }}</span>
                    @endif
                </div>
                <div class="pt-5 px-0.5">
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight group-hover:text-primary-600 transition-colors">{{ $service->title }}</h3>
                        <span class="mt-1 w-7 h-7 shrink-0 rounded-full border border-surface-200 flex items-center justify-center text-gray-400 group-hover:bg-gray-900 group-hover:border-gray-900 group-hover:text-white transition-colors duration-200">
                            <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                        </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-500 leading-relaxed line-clamp-2">{{ $service->short_description }}</p>
                    <div class="mt-4 pt-4 border-t border-surface-200 flex items-center justify-between">
                        <span class="text-sm font-bold text-gray-900">From <span class="text-accent-600">${{ number_format($service->starting_price ?? 0, 2) }}</span></span>
                        @if($service->delivery_time)
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                            <x-icon name="clock" class="w-3.5 h-3.5 text-gray-400" />
                            {{ $service->delivery_time }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-16 text-gray-400">Services are being added — check back soon.</div>
            @endforelse
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('services.index') }}" class="btn btn-lg btn-secondary">
                View All Services
                <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        </div>
    </div>
</section>

{{-- ============ BEFORE / AFTER ============ --}}
@if($beforeAfter->count())
<section class="py-20 lg:py-28 bg-surface-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="eyebrow">Before &amp; After</span>
            <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Drag the slider. <em class="italic text-accent-600">See the difference.</em></h2>
            <p class="mt-5 text-lg text-gray-500">No stock examples — real client work, edited in-house. The quality speaks for itself.</p>
        </div>

        <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($beforeAfter as $item)
            <x-before-after
                before="{{ asset('storage/' . $item->before_image) }}"
                after="{{ asset('storage/' . $item->after_image) }}"
                title="{{ $item->title }}"
            />
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('before-after') }}" class="btn btn-lg btn-secondary">
                View All Examples
                <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============ PORTFOLIO ============ --}}
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-10 items-end mb-14">
            <div class="lg:col-span-8">
                <span class="eyebrow">Portfolio</span>
                <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Work we're <em class="italic text-accent-600">proud of</em></h2>
                <p class="mt-5 text-lg text-gray-500 max-w-xl">Recent projects across fashion, jewelry, real estate and more.</p>
            </div>
            <div class="lg:col-span-4 lg:text-right">
                <a href="{{ route('portfolio.index') }}" class="btn btn-md btn-secondary">
                    View Full Portfolio
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @forelse($featuredPortfolio as $project)
            <a href="{{ route('portfolio.show', $project->slug) }}" class="group block">
                <div class="relative aspect-[4/3] overflow-hidden rounded-2xl border border-surface-200/80 bg-surface-100">
                    @if($project->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <x-icon name="image" class="w-14 h-14 text-gray-300" />
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400" aria-hidden="true"></div>
                    <div class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all duration-300">
                        <x-icon name="external-link" class="w-4 h-4" />
                    </div>
                </div>
                <div class="pt-4 px-0.5 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $project->category->name ?? 'Project' }}</div>
                        <h3 class="mt-0.5 text-lg font-bold text-gray-900 tracking-tight truncate group-hover:text-primary-600 transition-colors">{{ $project->title }}</h3>
                    </div>
                    @if($project->client)
                    <span class="shrink-0 text-xs text-gray-400 font-medium">{{ $project->client }}</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-16 text-gray-400">Portfolio coming soon.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============ DIGITAL PRODUCTS ============ --}}
@if($featuredProducts->count())
<section class="py-20 lg:py-28 bg-surface-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-10 items-end mb-14">
            <div class="lg:col-span-8">
                <span class="eyebrow">Digital Products</span>
                <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Pro tools to <em class="italic text-accent-600">level up your edits</em></h2>
                <p class="mt-5 text-lg text-gray-500 max-w-xl">Presets, actions and LUTs crafted by the same team behind our retouching.</p>
            </div>
            <div class="lg:col-span-4 lg:text-right">
                <a href="{{ route('products.index') }}" class="btn btn-md btn-secondary">
                    Browse All Products
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-10">
            @foreach($featuredProducts as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="group block">
                <div class="relative aspect-square overflow-hidden rounded-2xl border border-surface-200/80 bg-white">
                    @if($product->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-[1.06] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-surface-100 to-surface-200">
                        <x-icon name="package" class="w-12 h-12 text-gray-300" />
                    </div>
                    @endif
                    @if($product->sale_price && $product->sale_price < $product->price)
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-accent-500 text-gray-900 text-[11px] font-bold shadow-sm">Sale</span>
                    @endif
                </div>
                <div class="pt-4 px-0.5">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $product->category->name ?? 'Product' }}</div>
                    <h3 class="mt-0.5 text-sm font-bold text-gray-900 leading-snug group-hover:text-primary-600 transition-colors">{{ $product->title }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-base font-extrabold text-gray-900">${{ number_format($product->sale_price ?? $product->price, 2) }}</span>
                        @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="text-xs text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ WHY CHOOSE US ============ --}}
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="eyebrow">Why PhotoLabe</span>
            <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">What sets us <em class="italic text-accent-600">apart</em></h2>
        </div>

        <div class="mt-16 grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @php
                $features = [
                    ['icon' => 'shield', 'title' => 'Quality Guarantee', 'desc' => 'Every project passes a two-stage quality control review before delivery. If it is not perfect, we redo it free.'],
                    ['icon' => 'clock', 'title' => '12–24h Turnaround', 'desc' => 'Standard edits ship within 24 hours; express delivery is available in as little as 12. Rush requests welcome.'],
                    ['icon' => 'user', 'title' => 'Senior Editors Only', 'desc' => 'No outsourcing roulette. Your project is handled by a named specialist with years of commercial experience.'],
                    ['icon' => 'refresh', 'title' => 'Unlimited Revisions', 'desc' => 'Request changes until you are 100% satisfied — revisions are always free, no matter the package.'],
                    ['icon' => 'shield', 'title' => 'Secure & Private', 'desc' => 'Files are encrypted at rest, never shared, and purged after delivery. NDAs available for enterprise clients.'],
                    ['icon' => 'credit-card', 'title' => 'Transparent Pricing', 'desc' => 'Flat per-image quotes with volume discounts. No hidden fees, no surprises on the invoice.'],
                ];
            @endphp
            @foreach($features as $feature)
            <div class="group">
                <div class="flex items-start gap-4">
                    <span class="mt-0.5 w-11 h-11 shrink-0 rounded-xl bg-surface-100 border border-surface-200 flex items-center justify-center text-gray-700 group-hover:bg-accent-500 group-hover:border-accent-500 group-hover:text-gray-900 transition-colors duration-200">
                        <x-icon name="{{ $feature['icon'] }}" class="w-5 h-5" />
                    </span>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">{{ $feature['title'] }}</h3>
                        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ WORK PROCESS ============ --}}
<section class="py-20 lg:py-28 bg-surface-900 relative overflow-hidden">
    <div class="absolute inset-0 gradient-mesh opacity-30" aria-hidden="true"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="eyebrow !bg-white/10 !text-accent-300">How It Works</span>
            <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-white">From upload to delivery <em class="italic text-accent-400">in 4 steps</em></h2>
        </div>

        <div class="mt-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
            @php
                $steps = [
                    ['num' => '01', 'title' => 'Send your files', 'desc' => 'Submit your images through the quote form with any reference notes.'],
                    ['num' => '02', 'title' => 'Get your quote', 'desc' => 'A specialist reviews your request and sends a fixed quote within hours.'],
                    ['num' => '03', 'title' => 'We edit', 'desc' => 'Your editor works on the images and sends a preview for approval.'],
                    ['num' => '04', 'title' => 'Download & revise', 'desc' => 'Receive full-resolution files. Request unlimited free revisions anytime.'],
                ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="relative">
                @if(!$loop->last)
                <div class="hidden lg:block absolute top-6 left-[calc(50%+2.5rem)] w-[calc(100%-5rem)] border-t border-dashed border-white/15" aria-hidden="true"></div>
                @endif
                <div class="flex items-center gap-4 mb-5">
                    <span class="w-12 h-12 shrink-0 rounded-2xl bg-accent-500 text-gray-900 font-extrabold text-lg flex items-center justify-center shadow-lg shadow-accent-500/25">{{ $step['num'] }}</span>
                    <span class="text-xs font-bold uppercase tracking-widest text-white/50">Step {{ $i + 1 }}</span>
                </div>
                <h3 class="text-xl font-bold text-white tracking-tight">{{ $step['title'] }}</h3>
                <p class="mt-2 text-sm text-white/60 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">
                Start Your Project
                <x-icon name="arrow-right" class="w-5 h-5" />
            </a>
        </div>
    </div>
</section>

{{-- ============ TESTIMONIALS ============ --}}
@if($testimonials->count())
<section class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto text-center">
            <span class="eyebrow">Testimonials</span>
            <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Clients who <em class="italic text-accent-600">keep coming back</em></h2>
        </div>

        <div class="mt-16 grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @foreach($testimonials as $testimonial)
            <figure class="flex flex-col">
                <div class="flex gap-1 mb-4" role="img" aria-label="{{ $testimonial->rating }}-star rating">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-accent-500' : 'text-surface-200' }}" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <blockquote class="text-gray-700 leading-relaxed flex-1">“{{ $testimonial->content }}”</blockquote>
                <figcaption class="mt-6 pt-6 border-t border-surface-200 flex items-center gap-3">
                    @if($testimonial->avatar)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover">
                    @else
                    <span class="w-10 h-10 rounded-full bg-gray-900 text-white flex items-center justify-center text-sm font-bold">{{ mb_substr($testimonial->name, 0, 1) }}</span>
                    @endif
                    <span>
                        <span class="block text-sm font-bold text-gray-900">{{ $testimonial->name }}</span>
                        @if($testimonial->title || $testimonial->company)
                        <span class="block text-xs text-gray-500">{{ $testimonial->title }}{{ $testimonial->company ? ', ' . $testimonial->company : '' }}</span>
                        @endif
                    </span>
                </figcaption>
            </figure>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ BLOG ============ --}}
@if($latestPosts->count())
<section class="py-20 lg:py-28 bg-surface-50/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-10 items-end mb-14">
            <div class="lg:col-span-8">
                <span class="eyebrow">From the Blog</span>
                <h2 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">Guides &amp; <em class="italic text-accent-600">insights</em></h2>
                <p class="mt-5 text-lg text-gray-500 max-w-xl">Tips, tutorials and industry news from our editing experts.</p>
            </div>
            <div class="lg:col-span-4 lg:text-right">
                <a href="{{ route('blog.index') }}" class="btn btn-md btn-secondary">
                    Read More Articles
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-x-8 gap-y-12">
            @foreach($latestPosts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                <div class="relative aspect-[16/10] overflow-hidden rounded-2xl border border-surface-200/80 bg-surface-100">
                    @if($post->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <x-icon name="document" class="w-12 h-12 text-gray-300" />
                    </div>
                    @endif
                </div>
                <div class="pt-5 px-0.5">
                    <div class="flex items-center gap-3">
                        @if($post->category)
                        <span class="text-[11px] font-bold uppercase tracking-wider text-accent-600">{{ $post->category->name }}</span>
                        <span class="w-1 h-1 rounded-full bg-surface-300" aria-hidden="true"></span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $post->published_at?->diffForHumans() ?? '' }}</span>
                    </div>
                    <h3 class="mt-2 text-lg font-bold text-gray-900 tracking-tight leading-snug line-clamp-2 group-hover:text-primary-600 transition-colors">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $post->excerpt }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ FAQ ============ --}}
<section class="py-20 lg:py-28 bg-white" x-data="{ open: 0 }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-14">
            <div class="lg:col-span-4">
                <span class="eyebrow">FAQ</span>
                <h2 class="mt-5 text-4xl font-extrabold tracking-tight text-gray-900">Quick <em class="italic text-accent-600">answers</em></h2>
                <p class="mt-5 text-lg text-gray-500">Everything you need to know before starting a project.</p>
                <a href="{{ route('faq') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                    View all FAQs
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            </div>
            <div class="lg:col-span-8 space-y-3">
                @php
                    $faqs = [
                        ['q' => 'How long does a typical project take?', 'a' => 'Most standard projects are completed within 24–48 hours. Complex projects may take 3–5 business days, and rush delivery is available for urgent needs.'],
                        ['q' => 'What file formats do you accept?', 'a' => 'We accept all major image formats — JPG, PNG, TIFF, PSD and RAW files. Digital products are delivered as ZIP archives.'],
                        ['q' => 'Do you offer revisions?', 'a' => 'Yes — unlimited free revisions until you are completely satisfied. There is no limit on our standard and premium packages.'],
                        ['q' => 'How do I get a quote?', 'a' => 'Fill out the quote form with your requirements and upload a sample. Our team replies with a detailed fixed quote within a few hours.'],
                        ['q' => 'Is my data secure?', 'a' => 'Absolutely. Files are stored on encrypted servers, never shared with third parties, and removed after delivery. NDAs are available for enterprise clients.'],
                    ];
                @endphp
                @foreach($faqs as $i => $faq)
                <div class="rounded-2xl overflow-hidden bg-white border transition-shadow duration-200"
                    :class="open === {{ $i }} ? 'border-accent-400 shadow-lg shadow-accent-500/10' : 'border-surface-200'">
                    <button type="button" @click="open = open === {{ $i }} ? null : {{ $i }}"
                        :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                        aria-controls="faq-panel-{{ $i }}"
                        class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left">
                        <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                        <span class="w-7 h-7 shrink-0 rounded-full bg-surface-100 flex items-center justify-center transition-transform duration-300"
                            :class="open === {{ $i }} ? 'rotate-45 bg-accent-500' : ''">
                            <svg class="w-4 h-4 text-gray-600 transition-colors duration-300" :class="open === {{ $i }} ? 'text-gray-900' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        </span>
                    </button>
                    <div x-show="open === {{ $i }}" x-collapse id="faq-panel-{{ $i }}" x-cloak>
                        <p class="px-6 pb-6 text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============ CLOSING CTA ============ --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-16 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Ready to see what your images can <em class="italic text-accent-400">become?</em></h2>
                <p class="mt-4 text-white/70 text-lg">Send us a sample today — get a free, no-obligation quote from a real retoucher.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">
                        Get Your Free Quote
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">Talk to Us</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
