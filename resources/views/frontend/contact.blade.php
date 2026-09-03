@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')
<section class="page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Contact Us</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Have a question? We'd love to hear from you.</p>
    </div>
</section>
<section class="py-24">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="{{ route('contact.store') }}" method="POST" class="surface-card p-8 md:p-12">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="form-control-modern">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="form-control-modern">
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="mt-6">
                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                    class="form-control-modern">
                @error('subject') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mt-6">
                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
                <textarea id="message" name="message" rows="5" required
                    class="form-control-modern resize-none">{{ old('message') }}</textarea>
                @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mt-8">
                <button type="submit" class="btn-modern w-full px-8 py-4 bg-gradient-to-r from-primary-600 to-accent-600 text-white font-semibold rounded-2xl hover:from-primary-700 hover:to-accent-700 transition-all shadow-lg shadow-primary-500/20 text-lg">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</section>
@endsection