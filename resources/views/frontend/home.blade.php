@extends('layouts.app')

@section('seo')
    <x-seo-meta
        title="Professional Photo Editing & Creative Design Services"
        description="Transform your images into professional, market-ready visuals with expert photo editing and creative design services."
        :schema="[
            'type' => 'Organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'description' => 'Professional photo editing and creative design services.',
        ]"
    />
@endsection

@section('content')
{{-- 1. Hero Section --}}
<section class="relative bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 overflow-hidden">
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500 rounded-full blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-36">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-sm text-indigo-200 mb-8">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                Trusted by 500+ businesses worldwide
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-bold text-white leading-tight mb-6">
                Professional Photo Editing
                <span class="bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">& Creative Design</span>
                Services
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-10 leading-relaxed">
                Transform your images into professional, market-ready visuals. Expert retouching, background removal, color correction, and creative design.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('quote.create') }}" class="inline-flex items-center justify-center px-8 py-4 bg-indigo-600 text-white font-semibold rounded-full hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/25 text-lg">
                    Get a Free Quote
                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('portfolio.index') }}" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/20 text-white font-semibold rounded-full hover:bg-white/10 transition-all text-lg">
                    View Our Work
                </a>
            </div>
        </div>
    </div>
</section>

{{-- 2. Trust Statistics --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-3xl md:text-4xl font-bold text-indigo-600">500+</div>
                <div class="text-sm text-gray-500 mt-1">Happy Clients</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-indigo-600">10K+</div>
                <div class="text-sm text-gray-500 mt-1">Projects Completed</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-indigo-600">24h</div>
                <div class="text-sm text-gray-500 mt-1">Turnaround Time</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold text-indigo-600">99%</div>
                <div class="text-sm text-gray-500 mt-1">Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

{{-- 3. Featured Services --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Our Services</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">Professional Editing Services</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">From basic retouching to complex creative projects, we deliver exceptional quality every time.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredServices as $service)
                <a href="{{ route('services.show', $service->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
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
                        <div class="text-xs font-medium text-indigo-600 mb-2">{{ $service->category->name ?? 'Service' }}</div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-2">{{ $service->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $service->short_description }}</p>
                        @if($service->starting_price)
                            <div class="mt-4 text-sm font-semibold text-indigo-600">From ${{ number_format($service->starting_price, 2) }}</div>
                        @endif
                    </div>
                </a>
            @empty
                @foreach(range(1, 6) as $i)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                        <div class="aspect-[16/10] bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center">
                            <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="p-6">
                            <div class="h-4 bg-gray-200 rounded w-1/3 mb-3"></div>
                            <div class="h-5 bg-gray-200 rounded w-2/3 mb-2"></div>
                            <div class="h-3 bg-gray-100 rounded w-full"></div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-700">
                View All Services
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 4. Before / After Interactive --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Before & After</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">See the Difference</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Drag the slider to compare our editing work. The quality speaks for itself.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($beforeAfter as $item)
                <x-before-after
                    before="{{ asset('storage/' . $item->before_image) }}"
                    after="{{ asset('storage/' . $item->after_image) }}"
                    title="{{ $item->title }}"
                />
            @empty
                @foreach(range(1, 4) as $i)
                    <div class="rounded-2xl bg-gray-100 aspect-square animate-pulse"></div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('before-after') }}" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-700">
                View All Examples
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 5. Featured Portfolio --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Portfolio</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">Our Recent Work</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Browse through our latest projects showcasing our expertise across various industries.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($featuredPortfolio as $project)
                <a href="{{ route('portfolio.show', $project->slug) }}" class="group relative rounded-2xl overflow-hidden aspect-[4/3] bg-gray-100">
                    @if($project->featured_image)
                        <img src="{{ asset('storage/' . $project->featured_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                            <svg class="w-16 h-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <div class="text-xs font-medium text-indigo-300 mb-1">{{ $project->category->name ?? 'Project' }}</div>
                        <h3 class="text-lg font-bold text-white">{{ $project->title }}</h3>
                        @if($project->client)
                            <p class="text-sm text-gray-300 mt-1">Client: {{ $project->client }}</p>
                        @endif
                    </div>
                </a>
            @empty
                @foreach(range(1, 6) as $i)
                    <div class="rounded-2xl bg-gray-200 aspect-[4/3] animate-pulse"></div>
                @endforeach
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-700">
                View Full Portfolio
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- 6. Digital Products --}}
@if($featuredProducts->count())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Digital Products</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">Shop Premium Tools</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Professional presets, actions, brushes, and templates to elevate your workflow.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="aspect-square bg-gradient-to-br from-purple-100 to-pink-100 overflow-hidden">
                        @if($product->featured_image)
                            <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="text-xs font-medium text-purple-600 mb-1">{{ $product->category->name ?? 'Product' }}</div>
                        <h3 class="font-bold text-gray-900 group-hover:text-purple-600 transition-colors mb-2">{{ $product->title }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                            @if($product->sale_price)
                                <span class="text-sm text-gray-400 line-through">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-700">
                Browse All Products
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- 7. Why Choose Us --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Why Choose Us</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">What Sets Us Apart</h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $features = [
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Quality Guarantee', 'desc' => 'Every project undergoes strict quality control before delivery.'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Fast Turnaround', 'desc' => 'Most projects delivered within 24 hours. Rush service available.'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Expert Team', 'desc' => 'Professional designers with 10+ years of industry experience.'],
                    ['icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15', 'title' => 'Free Revisions', 'desc' => 'Unlimited revisions until you are 100% satisfied with the result.'],
                    ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Secure & Private', 'desc' => 'Your files are kept confidential with bank-level security.'],
                    ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'title' => 'Flexible Pricing', 'desc' => 'Competitive pricing with volume discounts for ongoing projects.'],
                ];
            @endphp

            @foreach($features as $feature)
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mb-5">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 8. Work Process --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">How It Works</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">Simple 4-Step Process</h2>
        </div>

        <div class="grid md:grid-cols-4 gap-8">
            @php
                $steps = [
                    ['num' => '01', 'title' => 'Submit Request', 'desc' => 'Fill out our quote form with your requirements and upload reference files.'],
                    ['num' => '02', 'title' => 'Get a Quote', 'desc' => 'We review your request and provide a detailed quote within 24 hours.'],
                    ['num' => '03', 'title' => 'We Edit', 'desc' => 'Our expert team works on your project with precision and attention to detail.'],
                    ['num' => '04', 'title' => 'Delivery', 'desc' => 'Receive your professionally edited files. Request revisions if needed.'],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="text-center relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold mx-auto mb-5 shadow-lg shadow-indigo-200">
                        {{ $step['num'] }}
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
                    @if(!$loop->last)
                        <div class="hidden md:block absolute top-8 left-[60%] w-[80%] h-px bg-gradient-to-r from-indigo-200 to-transparent"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 9. Testimonials --}}
@if($testimonials->count())
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Testimonials</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">What Our Clients Say</h2>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    @if($testimonial->rating)
                        <div class="flex gap-1 mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                    @endif
                    <p class="text-gray-600 leading-relaxed mb-6">"{{ $testimonial->content }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold text-sm">
                            {{ substr($testimonial->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">{{ $testimonial->name }}</div>
                            @if($testimonial->title || $testimonial->company)
                                <div class="text-xs text-gray-500">{{ $testimonial->title }}{{ $testimonial->company ? ' at ' . $testimonial->company : '' }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 10. Blog Preview --}}
@if($latestPosts->count())
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">Blog</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">Latest Insights</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Tips, tutorials, and industry news from our editing experts.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($latestPosts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="aspect-[16/10] bg-gradient-to-br from-indigo-100 to-purple-100 overflow-hidden">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-3">
                            @if($post->category)
                                <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full">{{ $post->category->name }}</span>
                            @endif
                            <span class="text-xs text-gray-400">{{ $post->published_at?->diffForHumans() ?? '' }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors mb-2 line-clamp-2">{{ $post->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $post->excerpt }}</p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-indigo-600 font-semibold hover:text-indigo-700">
                Read More Articles
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- 11. FAQ --}}
<section class="py-20 bg-gray-50" x-data="{ open: null }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-indigo-600 font-semibold text-sm uppercase tracking-wider">FAQ</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3 mb-4">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            @php
                $faqs = [
                    ['q' => 'How long does a typical project take?', 'a' => 'Most standard projects are completed within 24-48 hours. Complex projects may take 3-5 business days. Rush delivery is available for urgent needs.'],
                    ['q' => 'What file formats do you accept?', 'a' => 'We accept all major image formats including JPG, PNG, TIFF, PSD, and RAW files. For digital products, we provide ZIP archives.'],
                    ['q' => 'Do you offer revisions?', 'a' => 'Yes, we offer free revisions until you are completely satisfied with the result. There is no limit on revisions for our standard and premium plans.'],
                    ['q' => 'How do I get a quote?', 'a' => 'Simply fill out our Get a Quote form with your requirements. Our team will review your request and provide a detailed quote within 24 hours.'],
                    ['q' => 'Is my data secure?', 'a' => 'Absolutely. All files are stored on encrypted servers and are never shared with third parties. We can sign NDAs for enterprise clients.'],
                ];
            @endphp

            @foreach($faqs as $i => $faq)
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <button @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="w-full flex items-center justify-between p-5 text-left">
                        <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                        <svg class="w-5 h-5 text-gray-400 shrink-0 transition-transform" :class="open === {{ $i }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-5">
                        <p class="text-gray-500 leading-relaxed">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('faq') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 text-sm">View All FAQs →</a>
        </div>
    </div>
</section>
@endsection
