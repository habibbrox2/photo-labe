@extends('layouts.app')

@section('title', 'Unsubscribed')

@section('content')
<section class="py-24 bg-white">
    <div class="max-w-xl mx-auto px-4 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center">
            <x-icon name="check" class="w-8 h-8 text-green-600" />
        </div>
        <h1 class="mt-6 text-3xl font-extrabold text-gray-900">You're unsubscribed</h1>
        <p class="mt-3 text-gray-500">
            <strong>{{ $email }}</strong> has been removed from our newsletter.
            We're sorry to see you go — you can re-subscribe anytime from the footer of any page.
        </p>
        <a href="{{ route('home') }}" class="mt-8 inline-block btn">Back to Home</a>
    </div>
</section>
@endsection
