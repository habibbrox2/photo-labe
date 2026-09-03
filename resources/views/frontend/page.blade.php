@extends('layouts.app')
@section('title', $page->seo_title ?? $page->title)
@section('meta_description', $page->seo_description ?? '')

@section('content')
<x-page-hero
    title="{{ $page->title }}"
    :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => $page->title]]"
/>

<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($page->featured_image)
        <div class="mb-10 rounded-2xl overflow-hidden">
            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-auto">
        </div>
        @endif

        @if($page->content)
        <div class="prose prose-lg max-w-none prose-headings:tracking-tight prose-headings:text-gray-900 prose-headings:font-extrabold prose-p:text-gray-600 prose-a:text-accent-600 prose-strong:text-gray-900 prose-li:text-gray-600 prose-img:rounded-2xl">
            {!! $page->content !!}
        </div>
        @endif
    </div>
</section>
@endsection