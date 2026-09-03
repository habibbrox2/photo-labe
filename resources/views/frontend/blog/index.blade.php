@extends('layouts.app')
@section('title', 'Blog')
@section('content')

{{-- Hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Blog</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="max-w-3xl">
            <span class="eyebrow">Guides &amp; Tutorials</span>
            <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">Editing knowledge, <em class="italic text-accent-600">no fluff</em></h1>
            <p class="mt-5 text-lg text-gray-500">Practical tutorials and industry tips from the editors behind our client work.</p>
        </div>
    </div>
</section>

{{-- Featured post --}}
@if($posts->count())
<section class="py-14 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php $featured = $posts->first(); @endphp
        <a href="{{ route('blog.show', $featured->slug) }}" class="group grid lg:grid-cols-2 gap-10 items-center rounded-3xl border border-surface-200 overflow-hidden bg-white p-5 sm:p-8 hover:border-surface-300 transition-colors">
            <div class="relative aspect-[16/10] rounded-2xl overflow-hidden bg-surface-100 border border-surface-200/60">
                @if($featured->featured_image)
                <img loading="lazy" decoding="async" src="{{ asset('storage/' . $featured->featured_image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700">
                @else
                <div class="w-full h-full flex items-center justify-center"><x-icon name="document" class="w-14 h-14 text-gray-300" /></div>
                @endif
            </div>
            <div class="py-2 lg:pr-4">
                <div class="flex items-center gap-3">
                    @if($featured->category)
                    <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-accent-700 bg-accent-100 rounded-full px-3 py-1">{{ $featured->category->name }}</span>
                    @endif
                    <span class="text-xs text-gray-400 font-medium">Latest article</span>
                </div>
                <h2 class="mt-4 text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 leading-tight group-hover:text-primary-600 transition-colors">{{ $featured->title }}</h2>
                <p class="mt-3 text-gray-500 leading-relaxed">{{ $featured->excerpt }}</p>
                <div class="mt-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-full bg-gray-900 text-white flex items-center justify-center text-xs font-bold">{{ mb_substr($featured->author->name ?? 'A', 0, 1) }}</span>
                        <div class="text-xs">
                            <div class="font-bold text-gray-900">{{ $featured->author->name ?? 'PhotoLabe Team' }}</div>
                            <div class="text-gray-400 mt-0.5">{{ $featured->published_at?->format('M d, Y') ?? '' }} · {{ $featured->views_count }} views</div>
                        </div>
                    </div>
                    <span class="btn btn-md btn-secondary group-hover:border-gray-900 group-hover:bg-gray-900 group-hover:text-white transition-colors">Read article <x-icon name="arrow-right" class="w-4 h-4" /></span>
                </div>
            </div>
        </a>
    </div>
</section>
@endif

{{-- Remaining posts --}}
@if($posts->count() > 1 || !$posts->count())
<section class="pb-20 lg:pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($posts->count() > 1)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @foreach($posts->skip(1) as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group block">
                <div class="relative aspect-[16/10] rounded-2xl overflow-hidden border border-surface-200/80 bg-surface-100">
                    @if($post->featured_image)
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex items-center justify-center"><x-icon name="document" class="w-12 h-12 text-gray-300" /></div>
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
                    <div class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                        Read article
                        <x-icon name="arrow-right" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="text-center py-16 text-gray-400">
            <x-icon name="document" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
            <p class="text-lg font-semibold text-gray-600">No blog posts found yet.</p>
        </div>
        @endif
        <div class="mt-16">{{ $posts->links() }}</div>
    </div>
</section>
@endif

{{-- Closing CTA --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-14 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Prefer to leave the editing to <em class="italic text-accent-400">us?</em></h2>
                <p class="mt-4 text-white/70 text-lg">Put these tips into practice — or send us your images and get professional results today.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get Your Free Quote <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <a href="{{ route('services.index') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">Explore Services</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
