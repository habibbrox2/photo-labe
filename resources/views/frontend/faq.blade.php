@extends('layouts.app')
@section('title', 'FAQ')
@section('content')

<x-page-hero
    eyebrow="FAQ"
    title="Frequently asked questions"
    subtitle="Everything you need to know before starting a project. Can't find your answer? Just ask."
    :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'FAQ']]"
/>

<section class="py-16 lg:py-20 bg-white" x-data="{ open: 0 }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">
        @php
        $faqs = [
        ['q' => 'How long does a typical project take?', 'a' => 'Most standard projects are completed within 24–48 hours. Complex projects may take 3–5 business days, and rush delivery is available for urgent needs.'],
        ['q' => 'What file formats do you accept?', 'a' => 'We accept all major image formats including JPG, PNG, TIFF, PSD and RAW files. Digital products are delivered as ZIP archives.'],
        ['q' => 'Do you offer revisions?', 'a' => 'Yes — unlimited free revisions until you are completely satisfied. No limits on our standard and premium packages.'],
        ['q' => 'How do I get a quote?', 'a' => 'Fill out the quote form with your requirements and upload a sample. Our team replies with a detailed fixed quote within a few hours.'],
        ['q' => 'Is my data secure?', 'a' => 'Absolutely. Files are stored on encrypted servers, never shared with third parties, and removed after delivery. NDAs are available for enterprise clients.'],
        ];
        @endphp
        @foreach($faqs as $i => $faq)
        <div class="rounded-2xl overflow-hidden bg-white border transition-all duration-200"
            :class="open === {{ $i }} ? 'border-accent-400 shadow-lg shadow-accent-500/10' : 'border-surface-200'">
            <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                :aria-expanded="open === {{ $i }} ? 'true' : 'false'"
                :aria-controls="'faq-{{ $i }}'"
                class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left">
                <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                <span class="w-7 h-7 shrink-0 rounded-full bg-surface-100 flex items-center justify-center transition-transform duration-300"
                    :class="open === {{ $i }} ? 'rotate-45 bg-accent-500' : ''">
                    <svg class="w-4 h-4 text-gray-600" :class="open === {{ $i }} ? 'text-gray-900' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </span>
            </button>
            <div x-show="open === {{ $i }}" x-collapse id="faq-{{ $i }}" x-cloak>
                <p class="px-6 pb-6 text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach

        <div class="mt-10 rounded-3xl border border-surface-200 bg-surface-50/60 p-8 text-center">
            <h2 class="text-xl font-extrabold tracking-tight text-gray-900">Still have questions?</h2>
            <p class="mt-2 text-sm text-gray-500">We're happy to help — reach out any time.</p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('contact') }}" class="btn btn-md btn-primary">Contact Us</a>
                <a href="{{ route('quote.create') }}" class="btn btn-md btn-secondary">Get a Free Quote</a>
            </div>
        </div>
    </div>
</section>
@endsection
