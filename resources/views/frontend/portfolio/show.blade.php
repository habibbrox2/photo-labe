@extends('layouts.app')
@section('title', $project->title)
@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('portfolio.index') }}" class="text-indigo-300 text-sm hover:text-white mb-4 inline-block">← Back to Portfolio</a>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $project->title }}</h1>
        <div class="flex flex-wrap items-center gap-4 text-gray-300">
            @if($project->category)
            <span class="px-3 py-1 bg-white/10 rounded-full text-sm">{{ $project->category->name }}</span>
            @endif
            @if($project->client)
            <span class="text-sm">Client: {{ $project->client }}</span>
            @endif
        </div>
    </div>
</section>

<section class="py-20" x-data="portfolioGallery()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Featured Image --}}
        @if($project->featured_image)
        <div class="mb-12 rounded-2xl overflow-hidden">
            <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-auto">
        </div>
        @endif

        {{-- Description --}}
        @if($project->description)
        <div class="max-w-3xl mb-12 prose prose-lg">{!! $project->description !!}</div>
        @endif

        {{-- Gallery --}}
        @if($project->images->count())
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Gallery</h2>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($project->images as $index => $image)
                <div class="rounded-xl overflow-hidden bg-gray-100 cursor-pointer group"
                    @click="openLightbox({{ $index }})">
                    <div class="aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $image->alt ?? $project->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Tags --}}
        @if($project->tags->count())
        <div class="flex flex-wrap gap-2 mb-12">
            @foreach($project->tags as $tag)
            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-sm rounded-full font-medium">{{ $tag->name }}</span>
            @endforeach
        </div>
        @endif

        {{-- Lightbox --}}
        <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300" x-transition:leave="transition ease-in duration-200"
            class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center" style="display: none;"
            @click.self="closeLightbox()" @keydown.escape.window="closeLightbox()">
            <button @click="closeLightbox()" class="absolute top-4 right-4 text-white/70 hover:text-white z-10">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <button @click="prevImage()" class="absolute left-4 text-white/70 hover:text-white z-10">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button @click="nextImage()" class="absolute right-4 text-white/70 hover:text-white z-10">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <div class="max-w-5xl max-h-[85vh] px-16">
                <img :src="images[currentIndex]" :alt="'Gallery image ' + (currentIndex + 1)" class="max-w-full max-h-[80vh] object-contain mx-auto">
                <p class="text-white/60 text-sm text-center mt-4" x-text="(currentIndex + 1) + ' / ' + images.length"></p>
            </div>
        </div>

        {{-- Related Projects --}}
        @if($relatedProjects->count())
        <div class="border-t border-gray-200 pt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Projects</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($relatedProjects as $related)
                <a href="{{ route('portfolio.show', $related->slug) }}" class="group relative rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100">
                    @if($related->featured_image)
                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                        <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/60 to-transparent">
                        <h3 class="font-bold text-white text-sm">{{ $related->title }}</h3>
                        <p class="text-xs text-gray-300">{{ $related->category->name ?? '' }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    function portfolioGallery() {
        return {
            lightboxOpen: false,
            currentIndex: 0,
            images: @json($project->images->map(fn ($img) => asset('storage/' . $img->image))->values()),

            openLightbox(index) {
                this.currentIndex = index;
                this.lightboxOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeLightbox() {
                this.lightboxOpen = false;
                document.body.style.overflow = '';
            },

            nextImage() {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            },

            prevImage() {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        };
    }
</script>
@endpush
@endsection