@extends('layouts.app')
@section('title', 'About Us')
@section('content')
{{--
    Fallback copy for /about, used only while no About page exists in the CMS
    (Pages → About in the admin), or before the seeder has run. Once that page is
    published it takes over, so edits belong there rather than here.
--}}
<x-page-hero
    eyebrow="Our Studio"
    title="About PhotoLabe"
    subtitle="A professional photo editing and creative design studio helping businesses look their best."
    :breadcrumbs="[['label' => 'Home', 'url' => route('home')], ['label' => 'About']]"
/>
<section class="py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 prose prose-lg">
        <h2>Our Story</h2>
        <p>PhotoLabe is a professional photo editing and creative design studio dedicated to helping businesses and individuals transform their visual content into stunning, market-ready assets.</p>
        <h2>Our Mission</h2>
        <p>We combine technical expertise with creative vision to deliver exceptional results that exceed our clients' expectations.</p>
        <h2>Our Team</h2>
        <p>Our team consists of experienced designers, photographers, and retouching specialists with years of industry experience.</p>
    </div>
</section>
@endsection
