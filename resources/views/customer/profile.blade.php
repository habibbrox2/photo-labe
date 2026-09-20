@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<section class="bg-surface-50/70 py-12 lg:py-14 min-h-[70vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <span class="eyebrow">Account</span>
        <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900">My Profile</h1>

        <x-flash class="mt-6" />

        <div class="surface-card p-8 mt-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Profile Information</h2>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="flex items-center gap-6 mb-6">
                    <div class="w-20 h-20 bg-accent-100 rounded-full flex items-center justify-center text-accent-800 text-2xl font-extrabold overflow-hidden shrink-0">
                        @if($user->avatar)
                            <img loading="lazy" decoding="async" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($user->name, 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Avatar</label>
                        <input type="file" name="avatar" accept="image/*"
                            class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-surface-100 file:text-gray-700 hover:file:bg-surface-200 file:cursor-pointer file:transition-colors cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP — max 2 MB</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="form-control-modern {{ $errors->has('name') ? '!border-red-300' : '' }}">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="form-control-modern {{ $errors->has('email') ? '!border-red-300' : '' }}">
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="form-control-modern {{ $errors->has('phone') ? '!border-red-300' : '' }}">
                        @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    </div>
                    <div class="flex items-end">
                        <span class="text-sm text-gray-500">Member since <span class="font-semibold text-gray-700">{{ $user->created_at->format('M Y') }}</span></span>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-5 mt-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}"
                            class="form-control-modern {{ $errors->has('city') ? '!border-red-300' : '' }}">
                        @error('city') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}"
                            class="form-control-modern {{ $errors->has('country') ? '!border-red-300' : '' }}">
                        @error('country') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Timezone</label>
                        <input type="text" name="timezone" value="{{ old('timezone', $user->timezone) }}"
                            class="form-control-modern {{ $errors->has('timezone') ? '!border-red-300' : '' }}" placeholder="e.g. Asia/Dhaka">
                        @error('timezone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @endif
                    </div>
                </div>

                <div class="mt-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Address</label>
                    <textarea name="address" rows="2" class="form-control-modern {{ $errors->has('address') ? '!border-red-300' : '' }}">{{ old('address', $user->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @endif
                </div>

                <div class="mt-7 pt-6 border-t border-surface-200">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>

        @if(!$user->hasVerifiedEmail())
            <div class="mt-6 p-6 rounded-2xl bg-amber-50 border border-amber-200">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <span class="w-10 h-10 shrink-0 rounded-full bg-amber-500/15 flex items-center justify-center">
                        <x-icon name="warning" class="w-5 h-5 text-amber-600" />
                    </span>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-amber-900">Your email is not verified.</p>
                        <p class="text-sm text-amber-700">Please check your inbox for the verification link.</p>
                    </div>
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm bg-amber-600 text-white hover:bg-amber-700">Resend</button>
                    </form>
                </div>
            </div>
        @endif

        <div class="surface-card p-8 mt-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Change Password</h2>
            <form method="POST" action="{{ route('password.change.update') }}">
                @csrf @method('PUT')
                <div class="space-y-4 max-w-md">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required
                            class="form-control-modern {{ $errors->has('current_password') ? '!border-red-300' : '' }}">
                        @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                        <input type="password" name="password" required
                            class="form-control-modern {{ $errors->has('password') ? '!border-red-300' : '' }}">
                        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                            class="form-control-modern {{ $errors->has('password_confirmation') ? '!border-red-300' : '' }}">
                    </div>
                </div>
                <div class="mt-7 pt-6 border-t border-surface-200">
                    <button type="submit" class="btn btn-secondary border-gray-900">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</section>