@extends('admin.layouts.app')
@section('page-title', 'Edit User')

@section('content')
<div class="max-w-2xl">
    <x-breadcrumbs :items="[['label' => 'Users', 'url' => route('admin.users.index')], ['label' => 'Edit']]" />

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
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

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                        <select name="role" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" required>
                            <option value="customer" {{ $user->role === 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="designer" {{ $user->role === 'designer' ? 'selected' : '' }}>Designer</option>
                            <option value="editor" {{ $user->role === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" required>
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ $user->status === 'banned' ? 'selected' : '' }}>Banned</option>
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                    @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Country</label>
                        <input type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        @error('country')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Postal Code</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Company</label>
                        <input type="text" name="company" value="{{ old('company', $user->company) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        @error('company')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', $user->website) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" placeholder="https://example.com">
                    @error('website')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('address', $user->address) }}</textarea>
                    @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Timezone</label>
                    <input type="text" name="timezone" value="{{ old('timezone', $user->timezone) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none" placeholder="e.g. Asia/Dhaka">
                    @error('timezone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@endif
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
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-200">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection