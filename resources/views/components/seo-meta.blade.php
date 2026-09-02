{{--
    Usage:
    <x-seo-meta
        title="Page Title"
        description="Page description"
        image="{{ asset('storage/path/to/image.jpg') }}"
        url="{{ url()->current() }}"
        type="website"
    />

    Or with schema:
    <x-seo-meta
        title="Product Name"
        description="Product description"
        :schema="['type' => 'Product', 'name' => 'Product Name', 'price' => '29.99']"
    />
--}}
@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'url' => null,
    'type' => 'website',
    'schema' => null,
])

@php
    $pageTitle = $title ? $title . ' - ' . config('app.name') : config('app.name') . ' - Professional Photo Editing & Creative Design';
    $pageDescription = $description ?? config('app.name') . ' - Professional photo editing and creative design services. Transform your images into stunning visuals.';
    $pageImage = $image ?? '';
    $pageUrl = $url ?? url()->current();
@endphp

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">

{{-- Open Graph --}}
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $pageUrl }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
@if($pageImage)
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">
@if($pageImage)
    <meta name="twitter:image" content="{{ $pageImage }}">
@endif

{{-- Canonical --}}
<link rel="canonical" href="{{ $pageUrl }}">

{{-- Schema.org JSON-LD --}}
@if($schema)
<script type="application/ld+json">
{!! json_encode(array_merge([
    '@context' => 'https://schema.org',
    '@type' => $schema['type'] ?? 'WebPage',
    'name' => $schema['name'] ?? $pageTitle,
    'description' => $schema['description'] ?? $pageDescription,
    'url' => $pageUrl,
], array_filter([
    'image' => $schema['image'] ?? $pageImage,
    'logo' => $schema['logo'] ?? null,
    'price' => $schema['price'] ?? null,
    'priceCurrency' => $schema['priceCurrency'] ?? 'USD',
    'brand' => $schema['brand'] ?? null,
    'aggregateRating' => $schema['aggregateRating'] ?? null,
    'review' => $schema['review'] ?? null,
    'author' => $schema['author'] ?? null,
    'publisher' => $schema['publisher'] ?? ['@type' => 'Organization', 'name' => config('app.name')],
    'mainEntityOfPage' => $schema['mainEntityOfPage'] ?? ['@type' => 'WebPage', '@id' => $pageUrl],
], fn($v) => $v !== null))) !!}
</script>
@endif
