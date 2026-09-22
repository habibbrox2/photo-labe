@extends('layouts.app')

@php
$showPrice = \App\Models\Setting::flag('services_show_price', true);
$showProductPrice = \App\Models\Setting::flag('products_show_price', true);
@endphp

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
{{-- ============ HERO: IMAGE SLIDER ============ --}}
@php
    $slides = $heroSlides->filter(fn ($s) => ! empty($s->image))->take(8)->values();
    $defaultHeadline = \App\Models\HeroSlide::DEFAULT_HEADLINE;
@endphp
<script>window.__heroHeadlines = @json($slides->pluck('headline'));</script>
<section x-data="{
            active: 0,
            count: {{ $slides->count() }},
            headlines: [],
            timer: null,
            dragging: false,
            dragX: 0,
            startX: 0,
            startT: 0,
            width: 1,
            start() { this.stop(); this.timer = setInterval(() => this.next(), 6000) },
            stop() { if (this.timer) { clearInterval(this.timer); this.timer = null } },
            next() { this.active = (this.active + 1) % this.count },
            prev() { this.active = (this.active - 1 + this.count) % this.count },
            go(i) { this.active = i },
            onDown(e) {
                if (this.count < 2 || e.button !== 0 || e.target.closest('a, button')) return;
                this.dragging = true;
                this.startX = e.clientX;
                this.startT = performance.now();
                this.dragX = 0;
                this.width = this.$el.clientWidth || 1;
                try { this.$el.setPointerCapture(e.pointerId) } catch (_) {}
                this.stop();
            },
            onMove(e) {
                if (!this.dragging) return;
                this.dragX = e.clientX - this.startX;
            },
            onUp() {
                if (!this.dragging) return;
                const dt = performance.now() - this.startT;
                const threshold = Math.max(50, this.width * 0.15);
                const flick = Math.abs(this.dragX) > 30 && dt < 250;
                if (this.dragX <= -threshold || (flick && this.dragX < 0)) this.next();
                else if (this.dragX >= threshold || (flick && this.dragX > 0)) this.prev();
                this.dragging = false;
                this.dragX = 0;
                this.start();
            },
            onCancel() {
                this.dragging = false;
                this.dragX = 0;
                this.start();
            },
            slideStyle(i) {
                const dx = this.dragging ? this.dragX : 0;
                if (i === this.active) return { opacity: 1, transform: 'translateX(' + dx + 'px)', zIndex: 2 };
                if (!this.dragging || !dx) return { opacity: 0, transform: 'translateX(0px)', zIndex: 0 };
                const p = Math.min(1, Math.abs(dx) / this.width);
                if (dx < 0 && i === (this.active + 1) % this.count) return { opacity: p, transform: 'translateX(' + ((1 - p) * 60) + 'px)', zIndex: 1 };
                if (dx > 0 && i === (this.active - 1 + this.count) % this.count) return { opacity: p, transform: 'translateX(' + ((1 - p) * -60) + 'px)', zIndex: 1 };
                return { opacity: 0, transform: 'translateX(0px)', zIndex: 0 };
            },
        }"
        x-init="start(); headlines = window.__heroHeadlines || []"
        @pointerdown="onDown"
        @pointermove="onMove"
        @pointerup="onUp"
        @pointercancel="onCancel"
        @visibilitychange.document="document.hidden ? stop() : start()"
        class="relative overflow-hidden bg-gray-950 text-white select-none touch-pan-y cursor-grab active:cursor-grabbing"
        aria-roledescription="carousel"
        aria-label="Featured work">
    {{-- Slides (z-0 contains slide z-indexes so overlays stay on top) --}}
    <div class="absolute inset-0 z-0">
        @forelse($slides as $i => $slide)
        <div x-cloak
             :style="slideStyle({{ $i }})"
             :class="dragging ? '' : 'transition-[opacity,transform] duration-700 ease-out'"
             class="absolute inset-0"
             :aria-hidden="active !== {{ $i }}"
             role="group"
             aria-roledescription="slide"
             aria-label="{{ $i + 1 }} of {{ $slides->count() }}: {{ $slide->caption_text ?? $slide->caption_label ?? 'Slide' }}">
            @if(!empty($slide->link_url))<a href="{{ $slide->link_url }}" class="absolute inset-0 z-20" tabindex="-1" :aria-hidden="active !== {{ $i }}" aria-label="{{ $slide->caption_text ?? 'View slide' }}"></a>@endif
            <img src="{{ asset('storage/' . $slide->image) }}"
                 alt="{{ $slide->caption_text ?? $slide->caption_label ?? 'Slide ' . ($i + 1) }}"
                 class="w-full h-full object-cover pointer-events-none"
                 draggable="false"
                 x-bind:class="active === {{ $i }} ? 'kenburns' : ''"
                 loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async" fetchpriority="{{ $i === 0 ? 'high' : 'low' }}">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/60 to-gray-950/30" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-gray-950/85 via-gray-950/40 to-transparent" aria-hidden="true"></div>
        </div>
        @empty
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-950 to-black" aria-hidden="true"></div>
        @endforelse
    </div>

    {{-- Copy overlay --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-40 lg:pt-40 lg:pb-56">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2.5 bg-white/10 border border-white/15 backdrop-blur pl-1.5 pr-4 py-1.5 rounded-full">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-gray-900 bg-accent-500 rounded-full px-2.5 py-1">
                    <x-icon name="star" class="w-3 h-3" /> 4.9
                </span>
                <span class="text-sm font-medium text-white/80">Rated excellent by 500+ studios &amp; brands</span>
            </div>

            <h1 class="mt-7 text-[2.6rem] leading-[1.06] sm:text-6xl xl:text-[4.2rem] xl:leading-[1.02] font-extrabold tracking-tight text-white drop-shadow-lg">
                <span x-show="!headlines[active]">{!! $defaultHeadline !!}</span>
                <span x-show="headlines[active]" x-cloak x-text="headlines[active]"></span>
            </h1>

            <p class="mt-6 text-lg text-white/70 leading-relaxed max-w-xl">
                Retouching, background removal, color grading and creative design — delivered by specialists in as little as 12 hours, with unlimited free revisions.
            </p>

            <div class="mt-9 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient shadow-xl shadow-accent-500/25">
                    Get a Free Quote
                    <x-icon name="arrow-right" class="w-5 h-5" />
                </a>
                <a href="{{ route('portfolio.index') }}" class="btn btn-lg bg-white/10 border border-white/20 text-white backdrop-blur hover:bg-white/20">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                    View Our Work
                </a>
            </div>

            <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
                <div>
                    <div class="text-2xl font-extrabold text-white leading-none">24h</div>
                    <div class="mt-1 text-sm text-white/60">avg. turnaround</div>
                </div>
                <div class="w-px h-10 bg-white/20 hidden sm:block" aria-hidden="true"></div>
                <div>
                    <div class="text-2xl font-extrabold text-white leading-none">10k+</div>
                    <div class="mt-1 text-sm text-white/60">projects delivered</div>
                </div>
                <div class="w-px h-10 bg-white/20 hidden sm:block" aria-hidden="true"></div>
                <div>
                    <div class="text-2xl font-extrabold text-white leading-none">100%</div>
                    <div class="mt-1 text-sm text-white/60">revision guarantee</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide caption --}}
    @if($slides->count())
    <div class="absolute bottom-24 left-0 right-0 z-10 pointer-events-none">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <template x-for='(slide, i) in @json($slides->map(fn ($s) => ["label" => $s->caption_label ?? "", "text" => $s->caption_text ?? ""]))'
                      :key="i">
                <div x-show="active === i" x-cloak
                     x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                    @if($slides->where('caption_label')->isNotEmpty())
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-400" x-show="slide.label" x-text="slide.label"></div>
                    @endif
                    <div class="text-lg font-bold text-white" x-show="slide.text" x-text="slide.text"></div>
                </div>
            </template>
        </div>
    </div>
    @endif

    {{-- Controls --}}
    @if($slides->count() > 1)
    <div class="absolute inset-x-0 bottom-8 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" @click="prev(); start()" aria-label="Previous slide"
                        class="w-11 h-11 rounded-full bg-white/10 border border-white/20 backdrop-blur flex items-center justify-center text-white hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" @click="next(); start()" aria-label="Next slide"
                        class="w-11 h-11 rounded-full bg-white/10 border border-white/20 backdrop-blur flex items-center justify-center text-white hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-400 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
            <div class="flex items-center gap-2" role="tablist" aria-label="Choose slide">
                @foreach($slides as $i => $slide)
                <button type="button" role="tab" @click="go({{ $i }}); start()"
                        :aria-selected="active === {{ $i }}"
                        :aria-label="'Go to slide {{ $i + 1 }}: {{ $slide->caption_text ?? $slide->caption_label ?? 'Slide' }}'"
                        class="h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent-400"
                        x-bind:class="active === {{ $i }} ? 'w-8 bg-accent-400' : 'w-2 bg-white/40 hover:bg-white/70'"></button>
                @endforeach
            </div>
        </div>
    </div>
    @endif
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
                        @if($showPrice)
                        <span class="text-sm font-bold text-gray-900">From <span class="text-accent-600">{{ money($service->starting_price ?? 0) }}</span></span>
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
                    @if($product->on_sale)
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-accent-500 text-gray-900 text-[11px] font-bold shadow-sm">Sale</span>
                    @endif
                </div>
                <div class="pt-4 px-0.5">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $product->category->name ?? 'Product' }}</div>
                    <h3 class="mt-0.5 text-sm font-bold text-gray-900 leading-snug group-hover:text-primary-600 transition-colors">{{ $product->title }}</h3>
                    <div class="mt-2 flex items-baseline gap-2">
                        @if($showProductPrice)
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
