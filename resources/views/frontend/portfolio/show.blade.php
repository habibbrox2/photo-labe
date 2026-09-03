@extends('layouts.app')
@section('title', $project->title)
@section('content')
@php
    $galleryImages = $project->images->map(fn ($img) => asset('storage/' . $img->image))->values();
@endphp

{{-- Hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><a href="{{ route('portfolio.index') }}" class="hover:text-primary-600 transition-colors">Portfolio</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">{{ $project->title }}</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-10 items-end">
            <div class="lg:col-span-9">
                @if($project->category)
                <span class="eyebrow">{{ $project->category->name }}</span>
                @endif
                <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">{{ $project->title }}</h1>
                @if($project->client)
                <p class="mt-4 text-lg text-gray-500">A project for <span class="font-semibold text-gray-700">{{ $project->client }}</span></p>
                @endif
            </div>
            @if($project->url)
            <div class="lg:col-span-3 lg:text-right">
                <a href="{{ $project->url }}" target="_blank" rel="noopener" class="btn btn-md btn-secondary">Visit live project <x-icon name="external-link" class="w-4 h-4" /></a>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Body + lightbox share one Alpine gallery scope --}}
<section class="py-16 lg:py-20 bg-white" x-data="portfolioGallery({{ Illuminate\Support\Js::from($galleryImages) }})">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-14">
            {{-- Main column --}}
            <div class="lg:col-span-8 min-w-0 space-y-12">
                @if($project->featured_image)
                <div class="overflow-hidden rounded-3xl border border-surface-200/80 bg-surface-100">
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full aspect-[4/3] object-cover">
                </div>
                @endif

                @if($project->description)
                <div class="prose prose-lg max-w-none prose-headings:tracking-tight prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary-600 prose-strong:text-gray-900 prose-li:text-gray-600">
                    {!! $project->description !!}
                </div>
                @endif

                @if($project->images->count())
                <div>
                    <span class="eyebrow">Gallery</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">More from this project</h2>
                    <div class="mt-8 grid md:grid-cols-2 gap-5">
                        @foreach($project->images as $index => $image)
                        <button type="button" class="group relative rounded-2xl overflow-hidden bg-surface-100 border border-surface-200/80 text-left cursor-zoom-in focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent-500 focus-visible:ring-offset-2"
                            @click="openLightbox({{ $index }})"
                            aria-label="Open image {{ $index + 1 }} fullscreen">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->alt ?? $project->title }}"
                                    class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-500">
                            </div>
                            <span class="absolute inset-0 bg-gray-900/0 group-hover:bg-gray-900/10 transition-colors duration-300 flex items-end justify-end p-3" aria-hidden="true">
                                <span class="w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <x-icon name="external-link" class="w-4 h-4" />
                                </span>
                            </span>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($project->tags->count())
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-4">Services used</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($project->tags as $tag)
                        <span class="px-3.5 py-1.5 bg-surface-100 border border-surface-200 text-gray-600 text-sm rounded-full font-medium">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 space-y-5">
                    <div class="rounded-3xl border border-surface-200 bg-white p-7 shadow-[0_20px_50px_-30px_rgba(28,25,23,0.25)]">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-5">Project facts</h2>
                        <dl class="space-y-4 text-sm">
                            @if($project->client)
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-400 font-medium">Client</dt>
                                <dd class="font-semibold text-gray-900 text-right">{{ $project->client }}</dd>
                            </div>
                            @endif
                            @if($project->category)
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-400 font-medium">Category</dt>
                                <dd class="font-semibold text-gray-900 text-right">{{ $project->category->name }}</dd>
                            </div>
                            @endif
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-400 font-medium">Delivery</dt>
                                <dd class="font-semibold text-gray-900 text-right">12–24 hrs</dd>
                            </div>
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-400 font-medium">Revisions</dt>
                                <dd class="font-semibold text-gray-900 text-right">Unlimited</dd>
                            </div>
                        </dl>
                        <div class="mt-6 pt-6 border-t border-surface-200">
                            <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient w-full">Get results like this <x-icon name="arrow-right" class="w-4 h-4" /></a>
                            <p class="mt-3 text-center text-xs text-gray-400">Free quote · reply within hours</p>
                        </div>
                    </div>

                    @if($relatedProjects->count())
                    <div class="rounded-3xl border border-surface-200 bg-surface-50/60 p-6">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-5">More {{ $project->category->name ?? 'projects' }}</h2>
                        <ul class="space-y-3">
                            @foreach($relatedProjects as $related)
                            <li>
                                <a href="{{ route('portfolio.show', $related->slug) }}" class="group flex items-center gap-3">
                                    <span class="w-14 h-12 shrink-0 rounded-lg overflow-hidden bg-surface-200">
                                        @if($related->featured_image)
                                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $related->featured_image) }}" alt="" class="w-full h-full object-cover">
                                        @endif
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-bold text-gray-900 group-hover:text-primary-600 transition-colors truncate">{{ $related->title }}</span>
                                        @if($related->client)
                                        <span class="block text-xs text-gray-400">{{ $related->client }}</span>
                                        @endif
                                    </span>
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

    {{-- Lightbox — inside the gallery scope (only active when project images exist) --}}
    <div x-show="lightboxOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[60] bg-gray-950/95 flex items-center justify-center"
        style="display: none;"
        role="dialog" aria-modal="true" aria-label="Image viewer"
        x-on:keydown.escape.window="closeLightbox()" x-cloak>
        <button type="button" @click="closeLightbox()" aria-label="Close viewer" class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors z-10">
            <x-icon name="x" class="w-6 h-6" />
        </button>
        <button type="button" @click="prevImage()" aria-label="Previous image" class="absolute left-4 sm:left-8 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors z-10">
            <x-icon name="chevron-left" class="w-7 h-7" />
        </button>
        <button type="button" @click="nextImage()" aria-label="Next image" class="absolute right-4 sm:right-8 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors z-10">
            <x-icon name="chevron-right" class="w-7 h-7" />
        </button>
        <div class="max-w-5xl w-full px-16 sm:px-24" @click.self="closeLightbox()">
            <img loading="lazy" decoding="async" :src="images[currentIndex]" :alt="'Gallery image ' + (currentIndex + 1)" class="max-w-full max-h-[80vh] object-contain mx-auto rounded-xl shadow-2xl">
            <p class="text-white/60 text-sm text-center mt-5" x-text="(currentIndex + 1) + ' / ' + images.length"></p>
        </div>
    </div>
</section>

{{-- Closing CTA --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-14 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Want this level of quality <em class="italic text-accent-400">on your images?</em></h2>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get Your Free Quote <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <a href="{{ route('portfolio.index') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">Back to Portfolio</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
