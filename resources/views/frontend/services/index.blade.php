@extends('layouts.app')
@section('title', 'Our Services')
@section('content')
<section class="bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Our Services</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Professional photo editing and creative design services for every need.</p>
    </div>
</section>

<section class="py-20" x-data="{ activeCategory: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Category Tabs --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                All Services
            </button>
            @foreach($categories as $category)
                <button @click="activeCategory = '{{ $category->slug }}'"
                    :class="activeCategory === '{{ $category->slug }}' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all">
                    {{ $category->name }}
                    <span class="ml-1 text-xs opacity-70">({{ $category->services_count }})</span>
                </button>
            @endforeach
        </div>

        {{-- Services Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($services as $service)
                <div x-show="activeCategory === 'all' || activeCategory === '{{ $service->category->slug ?? '' }}'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    <a href="{{ route('services.show', $service->slug) }}" class="group block bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 h-full">
                        <div class="aspect-[16/10] bg-gradient-to-br from-indigo-100 to-purple-100 overflow-hidden">
                            @if($service->featured_image)
                                <img src="{{ asset('storage/' . $service->featured_image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-6">
                            <div class="text-xs font-medium text-indigo-600 mb-2">{{ $service->category->name ?? '' }}</div>
                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-2">{{ $service->title }}</h3>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $service->short_description }}</p>
                            <div class="flex items-center justify-between mt-4">
                                @if($service->starting_price)
                                    <span class="text-sm font-semibold text-indigo-600">From ${{ number_format($service->starting_price, 2) }}</span>
                                @endif
                                @if($service->delivery_time)
                                    <span class="text-xs text-gray-400">{{ $service->delivery_time }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-20 text-gray-400">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <p class="text-lg font-semibold text-gray-600">No services found</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $services->links() }}</div>
    </div>
</section>
@endsection
