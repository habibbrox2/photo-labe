@extends('layouts.app')
@section('title', 'Blog')
@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Blog</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Tips, tutorials, and industry insights from our team.</p>
    </div>
</section>
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="group card-hover surface-card overflow-hidden">
                <div class="aspect-[16/10] bg-gradient-to-br from-indigo-100 to-purple-100 overflow-hidden">
                    @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        @if($post->category)
                        <span class="text-xs font-bold text-primary-600 bg-primary-50 px-3 py-1.5 rounded-full">{{ $post->category->name }}</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $post->published_at?->diffForHumans() ?? '' }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-2 line-clamp-2">{{ $post->title }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $post->excerpt }}</p>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <p>No blog posts found.</p>
            </div>
            @endforelse
        </div>
        <div class="mt-12">{{ $posts->links() }}</div>
    </div>
</section>
@endsection