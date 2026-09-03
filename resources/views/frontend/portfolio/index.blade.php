@extends('layouts.app')
@section('title', 'Portfolio')
@section('content')

{{-- Hero --}}
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Portfolio</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-10 items-end">
            <div class="lg:col-span-8">
                <span class="eyebrow">Our Work</span>
                <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">
                    Real projects, <em class="italic text-accent-600">real results</em>
                </h1>
                <p class="mt-5 text-lg text-gray-500 max-w-xl">A selection of work across fashion, jewelry, real estate, wedding and brand design — every image edited in-house.</p>
            </div>
            <div class="lg:col-span-4 lg:text-right">
                <div class="inline-flex items-center gap-3 lg:justify-end">
                    <span class="text-4xl font-extrabold text-gray-900 leading-none">{{ $projects->total() }}</span>
                    <span class="text-sm text-gray-400 leading-tight">completed<br>projects</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Grid with category filter --}}
<section class="py-16 lg:py-20 bg-white" x-data="{ activeCategory: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Category pills --}}
        <div class="flex flex-wrap items-center gap-2.5 mb-14">
            <button @click="activeCategory = 'all'"
                :class="activeCategory === 'all' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900'"
                class="px-5 py-2.5 rounded-full text-sm font-semibold border transition-all duration-200 cursor-pointer">
                All Projects
            </button>
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->slug }}'"
                :class="activeCategory === '{{ $category->slug }}' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900'"
                class="px-5 py-2.5 rounded-full text-sm font-semibold border transition-all duration-200 cursor-pointer">
                {{ $category->name }}
                <span class="ml-1.5 text-xs opacity-60">{{ $category->projects_count }}</span>
            </button>
            @endforeach
        </div>

        {{-- Projects grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            @forelse($projects as $project)
            <div x-show="activeCategory === 'all' || activeCategory === '{{ $project->category->slug ?? '' }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-[0.98]"
                x-transition:enter-end="opacity-100 scale-100">
                <a href="{{ route('portfolio.show', $project->slug) }}" class="group block">
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden border border-surface-200/80 bg-surface-100">
                        @if($project->featured_image)
                        <img loading="lazy" decoding="async" src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-[1.05] transition-transform duration-700">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <x-icon name="image" class="w-14 h-14 text-gray-300" />
                        </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/55 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-400" aria-hidden="true"></div>
                        <div class="absolute top-3 right-3 w-9 h-9 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all duration-300">
                            <x-icon name="external-link" class="w-4 h-4" />
                        </div>
                        @if($project->tags->count())
                        <div class="absolute bottom-3 left-3 flex flex-wrap gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            @foreach($project->tags->take(3) as $tag)
                            <span class="text-[11px] font-medium bg-white/85 backdrop-blur text-gray-700 px-2.5 py-1 rounded-full">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="pt-4 px-0.5 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-[11px] font-semibold uppercase tracking-wider text-accent-600">{{ $project->category->name ?? 'Project' }}</div>
                            <h3 class="mt-0.5 text-lg font-bold text-gray-900 tracking-tight truncate group-hover:text-primary-600 transition-colors">{{ $project->title }}</h3>
                        </div>
                        @if($project->client)
                        <span class="shrink-0 text-xs text-gray-400 font-medium">{{ $project->client }}</span>
                        @endif
                    </div>
                </a>
            </div>
            @empty
            <div class="col-span-full text-center py-20 text-gray-400">
                <x-icon name="image" class="w-16 h-16 mx-auto text-gray-300 mb-4" />
                <p class="text-lg font-semibold text-gray-600">No projects found</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16">{{ $projects->links() }}</div>
    </div>
</section>

{{-- Closing CTA --}}
<section class="pb-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-[2rem] overflow-hidden bg-surface-900 px-8 py-14 md:px-16 text-center">
            <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>
            <div class="relative max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Like what you see? <em class="italic text-accent-400">Let's do yours.</em></h2>
                <p class="mt-4 text-white/70 text-lg">Send a sample and get a free quote from the same editors behind this work.</p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('quote.create') }}" class="btn btn-lg btn-gradient">Get Your Free Quote <x-icon name="arrow-right" class="w-5 h-5" /></a>
                    <a href="{{ route('services.index') }}" class="btn btn-lg !bg-white/5 !text-white border border-white/15 hover:!bg-white/10">Explore Services</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
