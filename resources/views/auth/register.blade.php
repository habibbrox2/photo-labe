@extends('layouts.app')
@section('title', 'Register')
@section('content')
<section class="min-h-[calc(100vh-8rem)] bg-gray-50 lg:grid lg:grid-cols-2">

    {{-- Brand panel (desktop) --}}
    <div class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-gradient-to-br from-primary-700 via-primary-600 to-accent-700 p-12 text-white">
        {{-- Decorative shapes --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute top-1/3 -left-32 w-80 h-80 bg-accent-400/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-full h-1/2 opacity-10"
            style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 28px 28px;"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                <div class="w-10 h-10 bg-white/15 backdrop-blur rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-xl">P</span>
                </div>
                <span class="text-xl font-bold tracking-tight">PhotoLabe</span>
            </a>
        </div>

        <div class="relative max-w-md">
            <h2 class="text-3xl font-bold leading-tight">Your editing studio, in your pocket.</h2>
            <p class="mt-4 text-primary-100 leading-relaxed">Join thousands of photographers and e-commerce teams who trust PhotoLabe with their images.</p>
            <ul class="mt-8 space-y-3.5 text-sm text-primary-50">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Free account — no setup fees, ever
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Quotes within 24 hours, guaranteed
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Cancel anytime — no lock-in
                </li>
            </ul>
        </div>

        <p class="relative text-xs text-primary-200">© {{ date('Y') }} PhotoLabe Studio. All rights reserved.</p>
    </div>

    {{-- Form panel --}}
    <div class="flex items-center justify-center px-4 sm:px-8 py-12">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <a href="{{ route('home') }}" class="lg:hidden inline-flex items-center gap-2 mb-8">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-600 to-accent-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="text-xl font-bold text-gray-900">PhotoLabe</span>
            </a>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create your account 🚀</h1>
                <p class="text-gray-500 mt-2">Free forever. Takes less than a minute.</p>
            </div>

            @include('components.social-buttons', ['label' => 'or sign up with email'])

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">

                @if(session('error'))
                    <div class="mb-4 p-3.5 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full name</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Jane Smith" required autofocus
                                class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-300 bg-red-50/50 focus:ring-red-500/20' : 'border-gray-200 focus:ring-accent-500/20' }} focus:border-accent-500 focus:ring-2 outline-none transition-all placeholder:text-gray-400">
                        </div>
                        @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required
                                class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-300 bg-red-50/50 focus:ring-red-500/20' : 'border-gray-200 focus:ring-accent-500/20' }} focus:border-accent-500 focus:ring-2 outline-none transition-all placeholder:text-gray-400">
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <div x-data="{ show: false }" class="relative">
                                <input type="password" id="password" name="password" placeholder="••••••••" required
                                    :type="show ? 'text' : 'password'"
                                    class="w-full pr-11 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-300 bg-red-50/50 focus:ring-red-500/20' : 'border-gray-200 focus:ring-accent-500/20' }} focus:border-accent-500 focus:ring-2 outline-none transition-all placeholder:text-gray-400">
                                <button type="button" @click="show = !show"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!show"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm8.5 0S19.5 6.5 12 6.5 3.5 12 3.5 12 8.5 17.5 12 17.5 20.5 12 20.5 12z"/></svg>
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="show" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7.5 0-11.5-7-11.5-7a19.4 19.4 0 013.35-4.15M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18M10.73 5.08A10.4 10.4 0 0112 5c7.5 0 11.5 7 11.5 7a19.4 19.4 0 01-2.31 3.31"/></svg>
                                </button>
                            </div>
                            @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm password</label>
                            <div x-data="{ show: false }" class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required
                                    :type="show ? 'text' : 'password'"
                                    class="w-full pr-11 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-300 bg-red-50/50 focus:ring-red-500/20' : 'border-gray-200 focus:ring-accent-500/20' }} focus:border-accent-500 focus:ring-2 outline-none transition-all placeholder:text-gray-400">
                                <button type="button" @click="show = !show"
                                    :aria-label="show ? 'Hide password' : 'Show password'"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!show"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm8.5 0S19.5 6.5 12 6.5 3.5 12 3.5 12 8.5 17.5 12 17.5 20.5 12 20.5 12z"/></svg>
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="show" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7.5 0-11.5-7-11.5-7a19.4 19.4 0 013.35-4.15M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18M10.73 5.08A10.4 10.4 0 0112 5c7.5 0 11.5 7 11.5 7a19.4 19.4 0 01-2.31 3.31"/></svg>
                                </button>
                            </div>
                            @error('password_confirmation') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-full">
                        Create Account
                    </button>

                    <p class="text-xs text-gray-400 text-center leading-relaxed">
                        By creating an account you agree to our
                        <a href="#" class="text-primary-500 hover:text-primary-600">Terms</a> and
                        <a href="#" class="text-primary-500 hover:text-primary-600">Privacy Policy</a>.
                    </p>
                </form>
            </div>

            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary-600 font-semibold hover:text-primary-700">Sign in</a>
            </p>
        </div>
    </div>
</section>
@endsection
