@extends('layouts.app')
@section('title', 'Verify Email')
@section('content')
<section class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-accent-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">P</span>
                </div>
                <span class="text-2xl font-bold text-gray-900">PhotoLabe</span>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Verify your email</h1>
            <p class="text-gray-500 mt-2">We've sent a verification link to your email address.</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="mb-6">
                <svg class="w-16 h-16 mx-auto text-primary-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>

            <p class="text-sm text-gray-600 mb-6">
                Click the link in the email we sent to <strong>{{ auth()->user()->email ?? '' }}</strong> to verify your account.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg w-full mb-3">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                    Or sign out and use a different email
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
