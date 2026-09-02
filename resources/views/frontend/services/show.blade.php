@extends('layouts.app')
@section('title', $service->seo_title ?? $service->title)
@section('meta_description', $service->seo_description ?? $service->short_description)
@section('content')
<section class="bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <a href="{{ route('services.index') }}" class="text-indigo-300 text-sm hover:text-white mb-4 inline-block">← Back to Services</a>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $service->title }}</h1>
            <p class="text-gray-300 text-lg">{{ $service->short_description }}</p>
        </div>
    </div>
</section>
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-12">
                @if($service->description)
                    <div class="prose prose-lg max-w-none">{!! $service->description !!}</div>
                @endif

                @if($service->features->count())
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Features</h2>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($service->features as $feature)
                                <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                                    <div class="w-6 h-6 bg-indigo-100 rounded-full flex items-center justify-center shrink-0 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ $feature->title }}</div>
                                        @if($feature->description)
                                            <div class="text-sm text-gray-500 mt-0.5">{{ $feature->description }}</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $beforeAfterItems = \App\Models\BeforeAfterProject::active()
                        ->where('service_id', $service->id)
                        ->ordered()
                        ->get();
                @endphp

                @if($beforeAfterItems->count())
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Before & After</h2>
                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach($beforeAfterItems as $item)
                                <x-before-after
                                    before="{{ asset('storage/' . $item->before_image) }}"
                                    after="{{ asset('storage/' . $item->after_image) }}"
                                    title="{{ $item->title }}"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Process Section --}}
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Our Process</h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        @php
                            $processSteps = [
                                ['num' => '01', 'title' => 'Upload', 'desc' => 'Send us your images through our secure upload system.'],
                                ['num' => '02', 'title' => 'Edit', 'desc' => 'Our expert team processes your images with precision.'],
                                ['num' => '03', 'title' => 'Deliver', 'desc' => 'Receive your professionally edited files within the timeline.'],
                            ];
                        @endphp
                        @foreach($processSteps as $step)
                            <div class="text-center p-6 bg-gray-50 rounded-2xl">
                                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-lg mx-auto mb-4">{{ $step['num'] }}</div>
                                <h3 class="font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                                <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-center">
                    <h3 class="text-2xl font-bold text-white mb-3">Ready to Get Started?</h3>
                    <p class="text-indigo-100 mb-6">Transform your images with our professional {{ strtolower($service->title) }} service.</p>
                    <a href="{{ route('quote.create') }}" class="inline-flex items-center px-8 py-3 bg-white text-indigo-600 font-semibold rounded-full hover:bg-indigo-50 transition-colors">
                        Get a Free Quote
                    </a>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                @if($service->pricing->count())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Pricing</h3>
                        @foreach($service->pricing as $plan)
                            <div class="p-4 rounded-xl {{ $plan->is_popular ? 'bg-indigo-50 border-2 border-indigo-200' : 'bg-gray-50 border border-gray-100' }} mb-3 last:mb-0">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-semibold text-gray-900">{{ $plan->plan_name }}</span>
                                    @if($plan->is_popular)
                                        <span class="text-xs bg-indigo-600 text-white px-2 py-0.5 rounded-full">Popular</span>
                                    @endif
                                </div>
                                <div class="text-2xl font-bold text-indigo-600">${{ number_format($plan->price, 2) }}</div>
                                @if($plan->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $plan->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-900 mb-3">Get Started</h3>
                    <p class="text-sm text-gray-500 mb-4">Ready to get started? Request a free quote now.</p>
                    <a href="{{ route('quote.create') }}" class="block w-full text-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-colors">
                        Get a Free Quote
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
