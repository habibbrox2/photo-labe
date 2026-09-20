@extends('layouts.app')
@section('title', ($preview ?? false ? 'Preview: ' : '').($page->seo_title ?? $page->title))
@section('meta_description', $page->seo_description ?? '')
@section('content')
@if($preview ?? false)<div class="bg-accent-500 text-gray-950 text-center text-sm font-bold py-2">Admin preview — this version may not be live.</div>@endif
@if(!empty($page->blocks))
    <x-page-blocks :page="$page" />
@else
    <x-page-hero :eyebrow="$page->eyebrow" :title="$page->title" :subtitle="$page->subtitle" :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => $page->title]]" />
    <section class="py-20 bg-white"><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg max-w-none">{!! render_shortcodes($page->content) !!}</div></section>
@endif
@endsection
