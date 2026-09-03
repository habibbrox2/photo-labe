<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#4c6ef5">

    {{-- Additional SEO Meta --}}
    <meta name="application-name" content="{{ config('app.name', 'PhotoLabe') }}">
    <meta name="msapplication-TileColor" content="#4c6ef5">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">

    {{-- Geo Meta --}}
    <meta name="geo.region" content="US-DC">
    <meta name="geo.placename" content="Design City">
    <meta name="geo.position" content="38.9072;-77.0369">
    <meta name="ICBM" content="38.9072, -77.0369">

    {{-- Business Info --}}
    <meta name="rating" content="general">
    <meta name="revisit-after" content="7 days">
    <meta name="expires" content="never">
    <meta name="language" content="English">
    <meta name="generator" content="Laravel">

    {{-- SEO --}}
    @hasSection('seo')
    @yield('seo')
    @else
    <title>@yield('title', config('app.name', 'PhotoLabe')) - {{ config('app.name', 'PhotoLabe') }}</title>
    <meta name="description" content="@yield('meta_description', 'Professional Photo Editing & Creative Design Services')">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Professional Photo Editing & Creative Design Services')">
    <meta property="og:image" content="@yield('og_image', asset('storage/demo/hero/main.jpg'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', config('app.name'))">
    <meta name="twitter:description" content="@yield('meta_description', 'Professional Photo Editing & Creative Design Services')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Default Organization Schema --}}
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "url": "{{ config('app.url') }}",
            "logo": "{{ asset('storage/demo/hero/main.jpg') }}",
            "description": "Professional photo editing and creative design services.",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "123 Creative Street",
                "addressLocality": "Design City",
                "addressRegion": "DC",
                "postalCode": "10001",
                "addressCountry": "US"
            },
            "contactPoint": {
                "@type": "ContactPoint",
                "telephone": "+1-555-123-4567",
                "contactType": "customer service",
                "availableLanguage": "English"
            },
            "sameAs": [
                "https://twitter.com/photolabe",
                "https://instagram.com/photolabe",
                "https://linkedin.com/company/photolabe",
                "https://facebook.com/photolabe"
            ]
        }
    </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="bg-surface-50 text-gray-900 antialiased font-sans">

    @include('components.header')

    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="surface-card bg-emerald-50/90 border-emerald-200 text-emerald-800 px-5 py-4" role="alert">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="surface-card bg-red-50/90 border-red-200 text-red-800 px-5 py-4" role="alert">
            {{ session('error') }}
        </div>
    </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>

</html>