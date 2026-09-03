@extends('layouts.app')
@section('title', 'Pricing')
@section('content')
<x-page-hero
    eyebrow="Transparent Pricing"
    title="Pricing"
    subtitle="Custom pricing for every project — no hidden fees, no surprises."
    :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'Pricing']]"
/>
<section class="py-16 lg:py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-500 text-lg">Every service is quoted per project based on complexity and volume. <a href="{{ route('quote.create') }}" class="text-accent-600 font-bold underline decoration-accent-300 underline-offset-4 hover:text-accent-700">Get a free quote</a> tailored to your specific needs — usually answered within a few hours.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('services.index') }}" class="btn btn-lg btn-primary">Browse Services</a>
            <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get a Free Quote <x-icon name="arrow-right" class="w-4 h-4" /></a>
        </div>
    </div>
</section>
@endsection