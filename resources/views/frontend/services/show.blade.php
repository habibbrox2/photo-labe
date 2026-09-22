@extends('layouts.app')
@section('title', $service->seo_title ?? $service->title)
@section('meta_description', $service->seo_description ?? $service->short_description)
@section('seo')
    <x-seo-meta
        :title="$seoData['title'] ?? ($service->seo_title ?? $service->title)"
        :description="$seoData['description'] ?? ($service->seo_description ?? $service->short_description)"
        :schema="$seoData['schema'] ?? []"
        :breadcrumb="$seoData['breadcrumb'] ?? []"
    />
@endsection
@section('content')
@php
$showPrice = \App\Models\Setting::flag('services_show_price', true);
@endphp

{{-- Hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="pt-6">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><a href="{{ route('services.index') }}" class="hover:text-primary-600 transition-colors">Services</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">{{ $service->title }}</li>
            </ol>
        </nav>

        <div class="py-12 lg:py-16">
            <div class="flex flex-wrap items-center gap-3">
                @if($service->category)
                <a href="{{ route('services.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-accent-700 bg-accent-100 rounded-full px-3.5 py-1.5 hover:bg-accent-200 transition-colors">{{ $service->category->name }}</a>
                @endif
                @if($service->delivery_time)
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 bg-white border border-surface-200 rounded-full px-3.5 py-1.5">
                    <x-icon name="clock" class="w-3.5 h-3.5 text-gray-400" />
                    {{ $service->delivery_time }} turnaround
                </span>
                @endif
            </div>
            <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.1] max-w-3xl">{{ $service->title }}</h1>
            <p class="mt-5 text-lg text-gray-500 leading-relaxed max-w-2xl">{{ $service->short_description }}</p>
            <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="flex items-center gap-5">
                    @if($showPrice && $service->starting_price)
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Starting at</div>
                        <div class="text-3xl font-extrabold text-gray-900 leading-tight">{{ money($service->starting_price) }}<span class="text-sm font-medium text-gray-400">/image</span></div>
                    </div>
                    @endif
                    <div class="w-px h-11 bg-surface-200" aria-hidden="true"></div>
                    <div>
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Revisions</div>
                        <div class="text-sm font-bold text-gray-900 mt-0.5 flex items-center gap-1.5"><x-icon name="check" class="w-4 h-4 text-accent-600" /> Unlimited, free</div>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">
                    Get a Free Quote
                    <x-icon name="arrow-right" class="w-5 h-5" />
                </a>
                <a href="#process" class="btn btn-lg btn-secondary">How it works</a>
            </div>
        </div>
    </div>
</section>

{{-- Body --}}
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-14">
            {{-- Main column --}}
            <div class="lg:col-span-2 space-y-16 min-w-0">
                {{-- Featured image --}}
                @if($service->featured_image)
                <div class="overflow-hidden rounded-3xl border border-surface-200/80 bg-surface-100">
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $service->featured_image) }}" alt="{{ $service->title }}" class="w-full aspect-[16/8] object-cover">
                </div>
                @endif

                {{-- Description --}}
                @if($service->description)
                <div>
                    <div class="prose prose-lg max-w-none prose-headings:tracking-tight prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary-600 prose-strong:text-gray-900 prose-li:text-gray-600 [&_h2]:text-2xl [&_h3]:text-xl">
                        {!! render_shortcodes($service->description) !!}
                    </div>
                </div>
                @endif

                {{-- Features --}}
                @if($service->features->count())
                <div>
                    <span class="eyebrow">What's included</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">Every order includes</h2>
                    <div class="mt-8 grid sm:grid-cols-2 gap-4">
                        @foreach($service->features as $feature)
                        <div class="flex items-start gap-3.5 p-5 bg-surface-50 border border-surface-200/80 rounded-2xl">
                            <span class="mt-0.5 w-7 h-7 shrink-0 rounded-full bg-accent-500/15 flex items-center justify-center">
                                <x-icon name="check" class="w-3.5 h-3.5 text-accent-700" />
                            </span>
                            <div>
                                <div class="font-bold text-gray-900">{{ $feature->title }}</div>
                                @if($feature->description)
                                <div class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $feature->description }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Pricing plans --}}
                @if($showPrice && $service->pricing->count())
                <div id="pricing">
                    <span class="eyebrow">Pricing</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">Simple, per-image pricing</h2>
                    <div class="mt-8 grid sm:grid-cols-3 gap-5">
                        @foreach($service->pricing as $plan)
                        <div class="relative p-6 rounded-2xl border flex flex-col {{ $plan->is_popular ? 'bg-gray-900 text-white border-gray-900 shadow-xl shadow-gray-900/20' : 'bg-white border-surface-200' }}">
                            @if($plan->is_popular)
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 inline-flex items-center gap-1 px-3 py-1 rounded-full bg-accent-500 text-gray-900 text-[11px] font-bold uppercase tracking-wide shadow">
                                <x-icon name="star" class="w-3 h-3" /> Popular
                            </span>
                            @endif
                            <div class="text-sm font-bold uppercase tracking-wider {{ $plan->is_popular ? 'text-accent-400' : 'text-gray-400' }}">{{ $plan->plan_name }}</div>
                            <div class="mt-3 text-3xl font-extrabold leading-none {{ $plan->is_popular ? 'text-white' : 'text-gray-900' }}">
                                {{ money($plan->price) }}
                                <span class="text-xs font-medium {{ $plan->is_popular ? 'text-white/50' : 'text-gray-400' }}">/ image</span>
                            </div>
                            @if($plan->description)
                            <p class="mt-3 text-sm leading-relaxed flex-1 {{ $plan->is_popular ? 'text-white/60' : 'text-gray-500' }}">{{ $plan->description }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-xs text-gray-400">Volume discounts available for bulk orders — ask when you request a quote.</p>
                </div>
                @endif

                {{-- Process --}}
                <div id="process">
                    <span class="eyebrow">How it works</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">From upload to delivery</h2>
                    <div class="mt-8 grid sm:grid-cols-3 gap-5">
                        @php
                        $processSteps = [
                            ['num' => '01', 'title' => 'Upload', 'desc' => 'Send your images through our secure quote form.'],
                            ['num' => '02', 'title' => 'Edit', 'desc' => 'A named specialist processes your images with precision.'],
                            ['num' => '03', 'title' => 'Deliver', 'desc' => 'Receive full-resolution files, then revise freely.'],
                        ];
                        @endphp
                        @foreach($processSteps as $step)
                        <div class="p-6 rounded-2xl border border-surface-200 bg-surface-50/60">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-10 h-10 shrink-0 rounded-xl bg-accent-500 text-gray-900 font-extrabold flex items-center justify-center">{{ $step['num'] }}</span>
                            </div>
                            <h3 class="font-bold text-gray-900">{{ $step['title'] }}</h3>
                            <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Before / after for this service --}}
                @php
                $beforeAfterItems = \App\Models\BeforeAfterProject::active()
                    ->where('service_id', $service->id)
                    ->ordered()
                    ->get();
                @endphp
                @if($beforeAfterItems->count())
                <div>
                    <span class="eyebrow">Before &amp; After</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">Real results from this service</h2>
                    <div class="mt-8 grid md:grid-cols-2 gap-6">
                        @foreach($beforeAfterItems as $item)
                        <x-before-after
                            before="{{ asset('storage/' . $item->before_image) }}"
                            after="{{ asset('storage/' . $item->after_image) }}"
                            title="{{ $item->title }}" />
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                <div class="lg:sticky lg:top-24 space-y-5">
                    <div class="rounded-3xl border border-surface-200 bg-white p-7 shadow-[0_20px_50px_-30px_rgba(28,25,23,0.25)]">
                        <div class="flex items-center justify-between">
                            @if($showPrice)
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">Starting from</span>
                            @else
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-gray-400">What you get</span>
                            @endif
                            @if($service->delivery_time)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-500"><x-icon name="clock" class="w-3 h-3" /> {{ $service->delivery_time }}</span>
                            @endif
                        </div>
                        @if($showPrice)
                        <div class="mt-2 text-4xl font-extrabold text-gray-900">{{ money($service->starting_price ?? 0) }}<span class="text-base font-medium text-gray-400"> / image</span></div>
                        @endif
                        <ul class="mt-6 space-y-3 text-sm text-gray-600">
                            <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3 h-3 text-accent-700" /></span>Free unlimited revisions</li>
                            <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3 h-3 text-accent-700" /></span>Preview before you pay</li>
                            <li class="flex items-center gap-2.5"><span class="w-5 h-5 rounded-full bg-accent-100 flex items-center justify-center shrink-0"><x-icon name="check" class="w-3 h-3 text-accent-700" /></span>Secure file handling</li>
                        </ul>
                        <a href="{{ route('quote.create') }}" class="btn btn-lg btn-primary w-full mt-7">Get a Free Quote</a>
                        <a href="{{ route('contact') }}" class="btn btn-lg btn-ghost w-full mt-2">Ask a question</a>
                        <p class="mt-4 text-center text-xs text-gray-400">Reply within a few hours, 7 days a week</p>
                    </div>

                    @if($service->category)
                    <div class="rounded-2xl border border-surface-200 bg-surface-50/60 p-6">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">More {{ $service->category->name }}</span>
                        <ul class="mt-3 space-y-2">
                            @foreach(\App\Models\Service::active()->where('category_id', $service->category_id)->where('id', '!=', $service->id)->limit(3)->get() as $related)
                            <li>
                                <a href="{{ route('services.show', $related->slug) }}" class="group flex items-center justify-between text-sm font-semibold text-gray-700 hover:text-primary-600 py-1.5 transition-colors">
                                    {{ $related->title }}
                                    <x-icon name="chevron-right" class="w-4 h-4 text-gray-300 group-hover:text-accent-500 transition-colors" />
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- Related services (controller-provided) --}}
@if($relatedServices->count())
<section class="pb-20 lg:pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-t border-surface-200 pt-14">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
                <div>
                    <span class="eyebrow">Keep exploring</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">Related services</h2>
                </div>
                <a href="{{ route('services.index') }}" class="btn btn-md btn-secondary">All Services <x-icon name="arrow-right" class="w-4 h-4" /></a>
            </div>
            <div class="grid md:grid-cols-3 gap-x-8 gap-y-10">
                @foreach($relatedServices as $related)
                <a href="{{ route('services.show', $related->slug) }}" class="group block">
                    <div class="aspect-[16/10] rounded-2xl border border-surface-200/80 bg-surface-100 overflow-hidden">
                        @if($related->featured_image)
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center"><x-icon name="image" class="w-12 h-12 text-gray-300" /></div>
                        @endif
                    </div>
                    <div class="pt-5">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-lg font-bold text-gray-900 tracking-tight group-hover:text-primary-600 transition-colors">{{ $related->title }}</h3>
                            <span class="mt-0.5 w-7 h-7 shrink-0 rounded-full border border-surface-200 flex items-center justify-center text-gray-400 group-hover:bg-gray-900 group-hover:text-white transition-colors"><x-icon name="arrow-right" class="w-3.5 h-3.5" /></span>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $related->short_description }}</p>
                        <div class="mt-4 pt-4 border-t border-surface-200 flex justify-between items-center">
                            @if($showPrice && $related->starting_price)
                            <span class="text-sm font-bold text-gray-900">From <span class="text-accent-600">{{ money($related->starting_price) }}</span></span>
                            @endif
                            @if($related->delivery_time)
                            <span class="text-xs text-gray-500">{{ $related->delivery_time }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- Closing CTA --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-16 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Ready to start your <em class="italic text-accent-400">{{ strtolower($service->title) }}</em> project?</h2>
                <p class="mt-4 text-white/70 text-lg">Send a sample and get a free, fixed quote — no commitment required.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get Your Free Quote <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <a href="{{ route('services.index') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">Browse other services</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
