@extends('layouts.app')
@section('title', 'Portfolio')
@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Our Portfolio</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Browse our collection of completed projects across various industries.</p>
    </div>
</section>

<section class="py-24" x-data="{ activeCategory: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Category Tabs --}}
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'bg-white text-gray-600 hover:bg-primary-50 border border-surface-200'"
                class="px-5 py-3 rounded-2xl text-sm font-semibold transition-all">
                All Projects
            </button>
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->slug }}'"
                :class="activeCategory === '{{ $category->slug }}' ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/20' : 'bg-white text-gray-600 hover:bg-primary-50 border border-surface-200'"
                class="px-5 py-3 rounded-2xl text-sm font-semibold transition-all">
                {{ $category->name }}
                <span class="ml-1 text-xs opacity-70">({{ $category->projects_count }})</span>
            </button>
            @endforeach
        </div>

        {{-- Projects Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($projects as $project)
            <div x-show="activeCategory === 'all' || activeCategory === '{{ $project->category->slug ?? '' }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">
                <a href="{{ route('portfolio.show', $project->slug) }}" class="group block relative rounded-3xl overflow-hidden aspect-[4/3] bg-gray-100 shadow-lg shadow-surface-900/10">
                    @if($project->featured_image)
                    <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                        <svg class="w-16 h-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <div class="text-xs font-medium text-indigo-300 mb-1">{{ $project->category->name ?? '' }}</div>
                        <h3 class="text-lg font-bold text-white">{{ $project->title }}</h3>
                        @if($project->client)
                        <p class="text-sm text-gray-300 mt-1">Client: {{ $project->client }}</p>
                        @endif
                        @if($project->tags->count())
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($project->tags->take(3) as $tag)
                            <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-lg font-semibold text-gray-600">No projects found</p>
            </div>
            @endforelse
        </div>

        <div class="mt-12">{{ $projects->links() }}</div>
    </div>
</section>
@endsection