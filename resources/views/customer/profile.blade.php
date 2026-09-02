@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<section class="bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">My Profile</h1>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <form>
                @csrf
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input type="text" value="{{ auth()->user()->name }}" class="w-full px-4 py-3 rounded-xl border border-gray-200" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" class="w-full px-4 py-3 rounded-xl border border-gray-200" readonly>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
