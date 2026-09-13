@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')

<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('home') }}" class="hover:text-primary-600 transition-colors">Home</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">Contact</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="max-w-3xl">
            <span class="eyebrow">We reply fast</span>
            <h1 class="mt-5 text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.08]">Let's <em class="italic text-accent-600">talk</em></h1>
            <p class="mt-5 text-lg text-gray-500">Questions about a project, a bulk order, or something else? Send a message — a real person replies within a few hours.</p>
        </div>
    </div>
</section>

<section class="py-14 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-12 gap-14">
            {{-- Contact info --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-24 space-y-4">
                    <a href="mailto:hello@photolabe.com" class="group flex items-start gap-4 p-5 rounded-2xl border border-surface-200 bg-white hover:border-accent-300 hover:shadow-md hover:shadow-accent-500/5 transition-all">
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-accent-500/15 flex items-center justify-center text-accent-700 group-hover:bg-accent-500 group-hover:text-gray-900 transition-colors">
                            <x-icon name="mail" class="w-5 h-5" />
                        </span>
                        <span>
                            <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Email us</span>
                            <span class="block text-sm font-bold text-gray-900 mt-1">hello@photolabe.com</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Replies within a few hours</span>
                        </span>
                    </a>
                    <a href="tel:+15551234567" class="group flex items-start gap-4 p-5 rounded-2xl border border-surface-200 bg-white hover:border-accent-300 hover:shadow-md hover:shadow-accent-500/5 transition-all">
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-accent-500/15 flex items-center justify-center text-accent-700 group-hover:bg-accent-500 group-hover:text-gray-900 transition-colors">
                            <x-icon name="phone" class="w-5 h-5" />
                        </span>
                        <span>
                            <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Call us</span>
                            <span class="block text-sm font-bold text-gray-900 mt-1">+1 (555) 123-4567</span>
                            <span class="block text-xs text-gray-500 mt-0.5">Mon–Fri, 9am–6pm ET</span>
                        </span>
                    </a>
                    <div class="flex items-start gap-4 p-5 rounded-2xl border border-surface-200 bg-white">
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-accent-500/15 flex items-center justify-center text-accent-700">
                            <x-icon name="map-pin" class="w-5 h-5" />
                        </span>
                        <span>
                            <span class="block text-xs font-bold uppercase tracking-wider text-gray-400">Studio</span>
                            <span class="block text-sm font-bold text-gray-900 mt-1">123 Creative Street</span>
                            <span class="block text-sm text-gray-500">Design City, DC 10001</span>
                        </span>
                    </div>

                    <div class="rounded-2xl p-6 bg-gray-900 text-white">
                        <div class="flex items-center gap-4">
                            <span class="w-12 h-12 rounded-2xl bg-accent-500 flex items-center justify-center">
                                <x-icon name="document" class="w-6 h-6 text-gray-900" />
                            </span>
                            <div>
                                <div class="text-sm font-bold">Need a price first?</div>
                                <p class="text-white/60 text-xs mt-0.5">Skip the back-and-forth — request a free quote directly.</p>
                            </div>
                        </div>
                        <a href="{{ route('quote.create') }}" class="btn btn-md btn-gradient w-full mt-5">Get a Free Quote <x-icon name="arrow-right" class="w-4 h-4" /></a>
                    </div>
                </div>
            </aside>

            {{-- Form --}}
            <div class="lg:col-span-8">
                <x-flash class="mb-6" />

                <form action="{{ route('contact.store') }}" method="POST" class="rounded-3xl border border-surface-200 bg-white p-7 md:p-10 shadow-[0_20px_50px_-30px_rgba(28,25,23,0.2)]">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name *</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control-modern">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control-modern">
                            @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">Subject *</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required class="form-control-modern">
                        @error('subject') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mt-6">
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Message *</label>
                        <textarea id="message" name="message" rows="6" required class="form-control-modern resize-none">{{ old('message') }}</textarea>
                        @error('message') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="mt-8 pt-8 border-t border-surface-200 flex flex-col sm:flex-row items-center gap-4">
                        <button type="submit" class="btn btn-lg btn-gradient w-full sm:w-auto">
                            Send Message
                            <x-icon name="send" class="w-4 h-4" />
                        </button>
                        <p class="text-xs text-gray-400">Prefer email? Write to hello@photolabe.com directly.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
