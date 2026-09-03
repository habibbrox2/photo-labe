@extends('layouts.app')

@section('seo')
    <x-seo-meta
        :title="$post->seo_title ?? $post->title"
        :description="$post->seo_description ?? $post->excerpt"
        :image="$post->featured_image ? asset('storage/' . $post->featured_image) : null"
        type="article"
        :schema="[
            'type' => 'Article',
            'name' => $post->title,
            'description' => $post->excerpt,
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : '',
            'author' => ['@type' => 'Person', 'name' => $post->author->name ?? 'Admin'],
            'publisher' => ['@type' => 'Organization', 'name' => config('app.name')],
            'datePublished' => $post->published_at?->toIso8601String(),
        ]"
    />
@endsection

@section('content')
<article class="py-12 lg:py-20 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav aria-label="Breadcrumb" class="mb-10">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-primary-600 transition-colors">Blog</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium truncate max-w-[180px]">{{ $post->title }}</li>
            </ol>
        </nav>

        <header>
            <div class="flex flex-wrap items-center gap-3">
                @if($post->category)
                <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-accent-700 bg-accent-100 rounded-full px-3.5 py-1.5">{{ $post->category->name }}</span>
                @endif
                <span class="text-sm text-gray-400">{{ $post->published_at?->format('M d, Y') ?? '' }}</span>
                <span class="text-sm text-gray-300" aria-hidden="true">·</span>
                <span class="text-sm text-gray-400">{{ $post->views_count }} {{ Str::plural('view', $post->views_count) }}</span>
            </div>

            <h1 class="mt-6 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.12]">{{ $post->title }}</h1>

            @if($post->author)
            <div class="mt-8 flex items-center gap-4 pb-8 border-b border-surface-200">
                <span class="w-11 h-11 rounded-full bg-gray-900 text-white flex items-center justify-center font-bold">{{ mb_substr($post->author->name, 0, 1) }}</span>
                <div>
                    <div class="font-bold text-gray-900 text-sm">{{ $post->author->name }}</div>
                    <div class="text-xs text-gray-500">Author</div>
                </div>
            </div>
            @endif
        </header>

        @if($post->featured_image)
        <div class="mt-10 rounded-3xl overflow-hidden border border-surface-200/80 bg-surface-100">
            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full aspect-[16/9] object-cover">
        </div>
        @endif

        <div class="prose prose-lg max-w-none mt-10 prose-headings:tracking-tight prose-headings:text-gray-900 prose-headings:font-extrabold prose-p:text-gray-600 prose-a:text-primary-600 prose-strong:text-gray-900 prose-li:text-gray-600 prose-blockquote:border-accent-400 prose-blockquote:text-gray-600 prose-img:rounded-2xl [&_h2]:mt-12 [&_img]:mx-auto">
            {!! $post->content !!}
        </div>

        @if($post->tags->count())
        <div class="flex flex-wrap gap-2 mt-12 pt-8 border-t border-surface-200">
            @foreach($post->tags as $tag)
            <span class="px-3.5 py-1.5 bg-surface-100 border border-surface-200 text-gray-600 text-sm rounded-full">{{ $tag->name }}</span>
            @endforeach
        </div>
        @endif

        {{-- Article CTA --}}
        <div class="mt-12 rounded-3xl border border-surface-200 bg-surface-50/60 p-8 text-center">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Put it into practice</h2>
            <p class="mt-2 text-gray-500 text-sm">Send us your images and get professional edits from the team that wrote this guide.</p>
            <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient mt-6">Get Your Free Quote <x-icon name="arrow-right" class="w-4 h-4" /></a>
        </div>
    </div>
</article>

{{-- Related posts --}}
@if($relatedPosts->count())
<section class="pb-20 lg:pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="border-t border-surface-200 pt-14">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-10">
                <div>
                    <span class="eyebrow">Keep reading</span>
                    <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">Related articles</h2>
                </div>
                <a href="{{ route('blog.index') }}" class="btn btn-md btn-secondary">All Articles <x-icon name="arrow-right" class="w-4 h-4" /></a>
            </div>
            <div class="grid md:grid-cols-3 gap-x-8 gap-y-12">
                @foreach($relatedPosts as $related)
                <a href="{{ route('blog.show', $related->slug) }}" class="group block">
                    <div class="relative aspect-[16/10] rounded-2xl overflow-hidden border border-surface-200/80 bg-surface-100">
                        @if($related->featured_image)
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center"><x-icon name="document" class="w-12 h-12 text-gray-300" /></div>
                        @endif
                    </div>
                    <div class="pt-5">
                        <div class="flex items-center gap-2.5">
                            @if($related->category)
                            <span class="text-[11px] font-bold uppercase tracking-wider text-accent-600">{{ $related->category->name }}</span>
                            <span class="w-1 h-1 rounded-full bg-surface-300" aria-hidden="true"></span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $related->published_at?->diffForHumans() ?? '' }}</span>
                        </div>
                        <h3 class="mt-2 text-lg font-bold text-gray-900 tracking-tight leading-snug line-clamp-2 group-hover:text-primary-600 transition-colors">{{ $related->title }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection
