@extends('layouts.app')
@section('title', 'FAQ')
@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Frequently Asked Questions</h1>
    </div>
</section>
<section class="py-24" x-data="{ open: null }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
        @php
        $faqs = [
        ['q' => 'How long does a typical project take?', 'a' => 'Most standard projects are completed within 24-48 hours. Complex projects may take 3-5 business days.'],
        ['q' => 'What file formats do you accept?', 'a' => 'We accept all major image formats including JPG, PNG, TIFF, PSD, and RAW files.'],
        ['q' => 'Do you offer revisions?', 'a' => 'Yes, we offer free revisions until you are completely satisfied.'],
        ['q' => 'How do I get a quote?', 'a' => 'Fill out our Get a Quote form. We will respond within 24 hours.'],
        ['q' => 'Is my data secure?', 'a' => 'Yes, all files are stored on encrypted servers and never shared with third parties.'],
        ];
        @endphp
        @foreach($faqs as $i => $faq)
        <div class="surface-card overflow-hidden transition-all duration-300" :class="open === {{ $i }} ? 'ring-2 ring-primary-500/20' : ''">
            <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" :aria-expanded="(open === {{ $i }}).toString()" class="w-full flex items-center justify-between p-6 text-left hover:bg-primary-50/40 transition-colors">
                <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                <span class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0 transition-all" :class="open === {{ $i }} ? 'rotate-180 bg-primary-600' : ''">
                    <svg class="w-4 h-4 transition-colors" :class="open === {{ $i }} ? 'text-white' : 'text-primary-600'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </span>
            </button>
            <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-5">
                <p class="text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection