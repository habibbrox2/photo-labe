@extends('admin.layouts.app')
@php
use Illuminate\Support\Facades\Storage;
@endphp
@section('page-title', 'My Profile')

@section('content')
<div class="max-w-2xl">
    <x-breadcrumbs :items="[['label' => 'Users', 'url' => route('admin.users.index')], ['label' => 'My Profile']]" />

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.users.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" required>
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" required>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Avatar</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="mt-2 w-12 h-12 rounded-full">
                    @endif
                    @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Change Password (optional)</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input type="password" name="password" placeholder="New password" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" minlength="8">
                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                        </div>
                        <div>
                            <input type="password" name="password_confirmation" placeholder="Confirm new password" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Save Changes</button>
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection