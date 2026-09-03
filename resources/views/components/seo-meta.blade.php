{{--
    Usage:
    <x-seo-meta
        title="Page Title"
        description="Page description"
        image="{{ asset('storage/path/to/image.jpg') }}"
        url="{{ url()->current() }}"
        type="website"
        :keywords="['photo editing', 'retouching']"
    />

    With schema:
    <x-seo-meta
        title="Product Name"
        description="Product description"
        :schema="[
            'type' => 'Product',
            'name' => 'Product Name',
            'description' => 'Product description',
            'image' => 'https://example.com/image.jpg',
            'price' => '29.99',
            'priceCurrency' => 'USD',
            'brand' => ['@type' => 'Brand', 'name' => 'PhotoLabe'],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => '4.9',
                'reviewCount' => '127'
            ]
        ]"
    />

    With breadcrumb:
    <x-seo-meta
        title="Page Title"
        :breadcrumb="[
            ['name' => 'Home', 'url' => '/'],
            ['name' => 'Services', 'url' => '/services'],
            ['name' => 'Photo Retouching', 'url' => '/services/photo-retouching']
        ]"
    />
--}}
@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
    'schema' => null,
    'breadcrumb' => null,
    'keywords' => null,
    'noindex' => false,
    'nofollow' => false,
    'published_time' => null,
    'modified_time' => null,
    'author' => null,
    'section' => null,
    'locale' => 'en_US',
])

@php
    $appName = config('app.name', 'PhotoLabe');
    $appUrl = config('app.url', 'https://photolabe.com');
    
    // Title (avoid appending the app name twice when the title already contains it)
    $pageTitle = $title
        ? (str_contains($title, $appName) ? $title : $title . ' - ' . $appName)
        : $appName . ' - Professional Photo Editing & Creative Design';
    
    // Description
    $pageDescription = $description ?? $appName . ' - Professional photo editing and creative design services. Transform your images into stunning visuals.';
    
    // Image
    $pageImage = $image ?? asset('storage/demo/hero/main.jpg');
    if ($pageImage && !str_starts_with($pageImage, 'http')) {
        $pageImage = $appUrl . $pageImage;
    }
    
    // URL
    $pageUrl = $url ?? url()->current();
    
    // Keywords
    $pageKeywords = $keywords ? implode(', ', $keywords) : 'photo editing, photo retouching, background removal, color correction, clipping path, jewelry retouching, product photography, creative design';
@endphp

{{-- Primary Meta --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
@if($pageKeywords)
    <meta name="keywords" content="{{ $pageKeywords }}">
@endif
<meta name="robots" content="{{ $noindex ? 'noindex' : 'index' }}, {{ $nofollow ? 'nofollow' : 'follow' }}">
<meta name="author" content="{{ $author ?? $appName }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:site_name" content="{{ $appName }}">
<meta property="og:locale" content="{{ $locale }}">
@if($pageImage)
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $title ?? $appName }}">
@endif
@if($published_time)
    <meta property="article:published_time" content="{{ $published_time }}">
@endif
@if($modified_time)
    <meta property="article:modified_time" content="{{ $modified_time }}">
@endif
@if($section)
    <meta property="article:section" content="{{ $section }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
@if($pageImage)
    <meta name="twitter:image" content="{{ $pageImage }}">
    <meta name="twitter:image:alt" content="{{ $title ?? $appName }}">
@endif
<meta name="twitter:site" content="@photolabe">
<meta name="twitter:creator" content="@photolabe">

{{-- Canonical --}}
<link rel="canonical" href="{{ $pageUrl }}">

{{-- Alternate for internationalization --}}
<link rel="alternate" hreflang="{{ str_replace('_', '-', $locale) }}" href="{{ $pageUrl }}">
<link rel="alternate" hreflang="x-default" href="{{ $pageUrl }}">

{{-- Preconnect for performance --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- Schema.org JSON-LD --}}
@if($schema || $breadcrumb)
<script type="application/ld+json">
{!! json_encode(array_filter([
    // Main schema
    $schema ? array_merge([
        '@context' => 'https://schema.org',
        '@type' => $schema['type'] ?? 'WebPage',
        'name' => $schema['name'] ?? $pageTitle,
        'description' => $schema['description'] ?? $pageDescription,
        'url' => $pageUrl,
        'image' => $schema['image'] ?? $pageImage,
        'inLanguage' => $locale,
        'isPartOf' => [
            '@type' => 'WebSite',
            'name' => $appName,
            'url' => $appUrl,
        ],
    ], array_filter([
        'headline' => $schema['headline'] ?? null,
        'datePublished' => $schema['datePublished'] ?? $published_time,
        'dateModified' => $schema['dateModified'] ?? $modified_time,
        'author' => $schema['author'] ?? null,
        'publisher' => $schema['publisher'] ?? [
            '@type' => 'Organization',
            'name' => $appName,
            'url' => $appUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $appUrl . '/storage/demo/hero/main.jpg',
            ],
        ],
        'mainEntityOfPage' => $schema['mainEntityOfPage'] ?? [
            '@type' => 'WebPage',
            '@id' => $pageUrl,
        ],
        'price' => $schema['price'] ?? null,
        'priceCurrency' => $schema['priceCurrency'] ?? 'USD',
        'brand' => $schema['brand'] ?? null,
        'offers' => $schema['offers'] ?? null,
        'aggregateRating' => $schema['aggregateRating'] ?? null,
        'review' => $schema['review'] ?? null,
        'breadcrumb' => $breadcrumb ? [
            '@type' => 'BreadcrumbList',
            'itemListElement' => collect($breadcrumb)->map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'],
                    'item' => str_starts_with($item['url'], 'http') ? $item['url'] : $appUrl . $item['url'],
                ];
            })->toArray(),
        ] : null,
    ], fn($v) => $v !== null)) : null,
    
    // Breadcrumb schema (if no main schema)
    !$schema && $breadcrumb ? [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => collect($breadcrumb)->map(function ($item, $index) use ($appUrl) {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => str_starts_with($item['url'], 'http') ? $item['url'] : $appUrl . $item['url'],
            ];
        })->toArray(),
    ] : null,
])) !!}
</script>
@endif
