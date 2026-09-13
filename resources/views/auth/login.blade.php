@extends('layouts.app')
@section('title', 'Login')
@section('content')
<section class="min-h-[calc(100vh-8rem)] bg-gray-50 lg:grid lg:grid-cols-2">

    {{-- Brand panel (desktop) --}}
    <div class="relative hidden lg:flex flex-col justify-between overflow-hidden bg-surface-950 p-12 text-white">
        <div class="absolute inset-0 gradient-mesh opacity-40" aria-hidden="true"></div>

        <div class="relative">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                <div class="w-10 h-10 bg-white/15 backdrop-blur rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-xl">P</span>
                </div>
                <span class="text-xl font-bold tracking-tight">PhotoLabe</span>
            </a>
        </div>

        <div class="relative max-w-md">
            <h2 class="text-3xl font-bold leading-tight">Professional photo editing, minus the back-and-forth.</h2>
            <p class="mt-4 text-white/70 leading-relaxed">Request quotes, track orders, and download finished edits — all from one place.</p>
            <ul class="mt-8 space-y-3.5 text-sm text-white/80">
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Real-time quotes from expert retouchers
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Order status & file delivery notifications
                </li>
                <li class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 text-accent-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Secure downloads & revision history
                </li>
            </ul>
        </div>

        <p class="relative text-xs text-white/40">© {{ date('Y') }} PhotoLabe Studio. All rights reserved.</p>
    </div>

    {{-- Form panel --}}
    <div class="flex items-center justify-center px-4 sm:px-8 py-12">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <a href="{{ route('home') }}" class="lg:hidden inline-flex items-center gap-2 mb-8">
                <div class="w-9 h-9 bg-gray-900 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-lg">P</span>
                </div>
                <span class="text-xl font-bold text-gray-900">PhotoLabe</span>
            </a>

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Welcome back</h1>
                <p class="text-gray-500 mt-2">Sign in to your account to continue</p>
            </div>

            @include('components.social-buttons', ['label' => 'or continue with email'])

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">

                <x-flash class="mb-4" />

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email address</label>
                        <div class="relative">
                            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus
                                class="w-full pl-11 pr-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-300 bg-red-50/50 focus:ring-red-500/20' : 'border-gray-200 focus:ring-accent-500/20' }} focus:border-accent-500 focus:ring-2 outline-none transition-all placeholder:text-gray-400">
                        </div>
                        @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <div x-data="{ show: false }" class="relative">
                            <svg class="w-5 h-5 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input type="password" id="password" name="password" placeholder="••••••••" required
                                :type="show ? 'text' : 'password'"
                                class="w-full pl-11 pr-12 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-300 bg-red-50/50 focus:ring-red-500/20' : 'border-gray-200 focus:ring-accent-500/20' }} focus:border-accent-500 focus:ring-2 outline-none transition-all placeholder:text-gray-400">
                            <button type="button" @click="show = !show"
                                :aria-label="show ? 'Hide password' : 'Show password'"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!show"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm8.5 0S19.5 6.5 12 6.5 3.5 12 3.5 12 8.5 17.5 12 17.5 20.5 12 20.5 12z"/></svg>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="show" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-7.5 0-11.5-7-11.5-7a19.4 19.4 0 013.35-4.15M9.88 9.88a3 3 0 104.24 4.24M3 3l18 18M10.73 5.08A10.4 10.4 0 0112 5c7.5 0 11.5 7 11.5 7a19.4 19.4 0 01-2.31 3.31"/></svg>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-full">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-primary-600 font-semibold hover:text-primary-700">Create one free</a>
            </p>
        </div>
    </div>
</section>
@endsection
