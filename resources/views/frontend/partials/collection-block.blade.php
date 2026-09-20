@php
    $limit = min(12, max(1, (int) ($data['limit'] ?? 3)));
    $source = $data['source'] ?? 'services';
    $items = match($source) {
        'portfolio' => \App\Models\PortfolioProject::active()->featured()->ordered()->limit($limit)->get(),
        'products' => \App\Models\Product::active()->featured()->ordered()->limit($limit)->get(),
        'testimonials' => \App\Models\Testimonial::active()->featured()->limit($limit)->get(),
        default => \App\Models\Service::active()->featured()->ordered()->limit($limit)->get(),
    };
@endphp
<section class="py-16 lg:py-20 bg-white"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">@if(!empty($data['heading']))<h2 class="text-3xl font-extrabold tracking-tight mb-10">{{ $data['heading'] }}</h2>@endif<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">@forelse($items as $item)<article class="surface-card overflow-hidden">@if($item->featured_image ?? false)<img src="{{ asset('storage/'.$item->featured_image) }}" alt="{{ $item->title ?? $item->name }}" class="w-full aspect-[16/10] object-cover" loading="lazy">@endif<div class="p-5"><h3 class="font-bold text-gray-900">{{ $item->title ?? $item->name }}</h3><p class="mt-2 text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($item->short_description ?? $item->description ?? $item->content ?? '', 130) }}</p></div></article>@empty<p class="text-sm text-gray-500">There is no published content to show yet.</p>@endforelse</div></div></section>
