@extends('layouts.app')
@section('title', 'Before & After')
@section('content')

<x-page-hero
    eyebrow="Proof of work"
    title="Before &amp; after"
    subtitle="Drag the sliders to compare — every pair below is real client work edited in-house."
    :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Before & After']]"
/>

<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($items->count())
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @foreach($items as $item)
            <figure>
                <x-before-after
                    before="{{ asset('storage/' . $item->before_image) }}"
                    after="{{ asset('storage/' . $item->after_image) }}"
                    title="{{ $item->title }}" />
                @if($item->description)
                <figcaption class="mt-4 text-sm text-gray-500 text-center leading-relaxed">{{ $item->description }}</figcaption>
                @endif
            </figure>
            @endforeach
        </div>
        @else
        <div class="text-center text-gray-400 py-20">
            <x-icon name="image" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
            <p class="text-lg font-semibold text-gray-600 mb-2">No examples yet</p>
            <p class="text-sm">Check back soon for before &amp; after editing examples.</p>
        </div>
        @endif
    </div>
</section>

{{-- Closing CTA --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-14 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Your images could be the next <em class="italic text-accent-400">transformation</em></h2>
                <p class="mt-4 text-white/70 text-lg">Send us a sample and see what a professional editor can do.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get Your Free Quote <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <a href="{{ route('services.index') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">Explore Services</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
