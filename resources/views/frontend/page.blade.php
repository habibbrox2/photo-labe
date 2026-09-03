@extends('layouts.app')
@section('title', $page->seo_title ?? $page->title)
@section('meta_description', $page->seo_description ?? '')

@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $page->title }}</h1>
    </div>
</section>

<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($page->featured_image)
        <div class="mb-10 rounded-2xl overflow-hidden">
            <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto">
        </div>
        @endif

        @if($page->content)
        <div class="prose prose-lg prose-indigo max-w-none">
            {!! $page->content !!}
        </div>
        @endif
    </div>
</section>
@endsection