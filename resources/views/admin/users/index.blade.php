@extends('admin.layouts.app')
@php
use Illuminate\Support\Facades\Storage;
@endphp
@section('page-title', 'Users')

@section('content')
<x-breadcrumbs :items="[['label' => 'Users', 'url' => route('admin.users.index')]]" />
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $users->total() }} total users</p>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="role" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
        <option value="">All Roles</option>
        <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="editor" {{ request('role') === 'editor' ? 'selected' : '' }}>Editor</option>
        <option value="designer" {{ request('role') === 'designer' ? 'selected' : '' }}>Designer</option>
        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Search</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Phone</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">City</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Joined</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full overflow-hidden shrink-0 {{ $user->avatar ? 'ring-2 ring-primary-100' : 'bg-primary-100' }} flex items-center justify-center text-primary-600 text-sm font-semibold">
                                @if($user->avatar)
                                    <img loading="lazy" decoding="async" src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    {{ substr($user->name, 0, 1) }}
                                @endif
                            </div>
                            <span class="font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->phone ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->city ?? '—' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $user->role === 'super_admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'admin' ? 'bg-blue-100 text-blue-700' : ($user->role === 'editor' ? 'bg-emerald-100 text-emerald-700' : ($user->role === 'designer' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600'))) }}">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : ($user->status === 'inactive' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">{{ ucfirst($user->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.users.edit', $user) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">Edit</a>
                        @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-gray-400">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection