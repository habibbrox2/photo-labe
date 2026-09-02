@extends('layouts.app')
@section('title', 'Pricing')
@section('content')
<section class="bg-gradient-to-br from-gray-900 via-indigo-950 to-purple-950 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Pricing</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Choose the plan that works best for your needs.</p>
    </div>
</section>
<section class="py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-gray-500 text-lg">Custom pricing available for all services. <a href="{{ route('quote.create') }}" class="text-indigo-600 font-semibold hover:text-indigo-700">Get a free quote</a> tailored to your specific needs.</p>
    </div>
</section>
@endsection
