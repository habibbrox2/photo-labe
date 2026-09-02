@extends('layouts.app')

@section('seo')
    <x-seo-meta
        title="{{ $post->seo_title ?? $post->title }}"
        description="{{ $post->seo_description ?? $post->excerpt }}"
        image="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : '' }}"
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
<article class="py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('blog.index') }}" class="text-indigo-600 text-sm hover:text-indigo-700 mb-6 inline-block">← Back to Blog</a>

        <div class="flex items-center gap-3 mb-4">
            @if($post->category)
                <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ $post->category->name }}</span>
            @endif
            <span class="text-sm text-gray-400">{{ $post->published_at?->format('M d, Y') ?? '' }}</span>
            <span class="text-sm text-gray-400">· {{ $post->views_count }} views</span>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $post->title }}</h1>

        @if($post->author)
            <div class="flex items-center gap-3 mb-8 pb-8 border-b border-gray-100">
                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold text-sm">
                    {{ substr($post->author->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-900 text-sm">{{ $post->author->name }}</div>
                    <div class="text-xs text-gray-500">Author</div>
                </div>
            </div>
        @endif

        @if($post->featured_image)
            <div class="rounded-2xl overflow-hidden mb-8">
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-auto">
            </div>
        @endif

        <div class="prose prose-lg max-w-none">{!! $post->content !!}</div>

        @if($post->tags->count())
            <div class="flex flex-wrap gap-2 mt-8 pt-8 border-t border-gray-100">
                @foreach($post->tags as $tag)
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </div>
</article>
@endsection
