@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['revenue'], 2) }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['orders'] }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pending Quotes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['pending_quotes'] }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['customers'] }}</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Services</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $stats['services'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Portfolio Projects</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $stats['portfolio'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Products</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $stats['products'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm text-gray-500">Blog Posts</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $stats['blog_posts'] }}</p>
        </div>
    </div>

    {{-- Notifications --}}
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h3 class="font-semibold text-gray-900">
                    Notifications
                    @if($unreadNotifications > 0)
                        <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">{{ $unreadNotifications }} unread</span>
                    @endif
                </h3>
                <a href="{{ route('admin.notifications.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">View all →</a>
            </div>
            @if($unreadNotifications > 0)
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Mark all as read</button>
                </form>
            @endif
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <a href="{{ route('admin.notifications.open', $notification) }}" class="px-5 py-3 flex items-start justify-between hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="text-sm font-medium {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900' }}">
                            @if(!$notification->read_at)
                                <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-2"></span>
                            @endif
                            {{ $notification->data['title'] ?? 'Notification' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap ml-4">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">No notifications yet.</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Recent Quotes</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentQuotes as $quote)
                    <a href="{{ route('admin.quotes.show', $quote) }}" class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $quote->name }}</p>
                            <p class="text-xs text-gray-500">{{ $quote->email }} · {{ $quote->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $quote->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $quote->status === 'quoted' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $quote->status === 'accepted' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $quote->status === 'converted' ? 'bg-purple-100 text-purple-700' : '' }}">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-gray-400 text-sm">No quotes yet.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Recent Orders</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentOrders as $order)
                    <a href="{{ route('admin.orders.show', $order) }}" class="px-5 py-3 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->name ?? 'N/A' }} · {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-gray-400 text-sm">No orders yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection