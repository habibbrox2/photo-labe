@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Welcome Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }} 👋</h1>
            <p class="text-gray-500 mt-1">Here's an overview of your account.</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['active_orders'] }}</div>
                        <div class="text-xs text-gray-500">Active Orders</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['pending_quotes'] }}</div>
                        <div class="text-xs text-gray-500">Pending Quotes</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['completed_orders'] }}</div>
                        <div class="text-xs text-gray-500">Completed</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_spent'], 2) }}</div>
                        <div class="text-xs text-gray-500">Total Spent</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('quote.create') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl p-4 flex items-center gap-3 hover:shadow-lg transition-shadow">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span class="font-semibold text-sm">New Quote</span>
            </a>
            <a href="{{ route('products.index') }}" class="bg-white rounded-2xl p-4 border border-gray-100 flex items-center gap-3 hover:shadow-md transition-shadow">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span class="font-semibold text-sm text-gray-900">Shop</span>
            </a>
            <a href="{{ route('account.orders') }}" class="bg-white rounded-2xl p-4 border border-gray-100 flex items-center gap-3 hover:shadow-md transition-shadow">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="font-semibold text-sm text-gray-900">My Orders</span>
            </a>
            <a href="{{ route('contact') }}" class="bg-white rounded-2xl p-4 border border-gray-100 flex items-center gap-3 hover:shadow-md transition-shadow">
                <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span class="font-semibold text-sm text-gray-900">Contact</span>
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            {{-- Recent Orders --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('account.orders') }}" class="text-sm text-indigo-600 hover:text-indigo-700">View all →</a>
                </div>
                @if($recentOrders->count())
                    <div class="divide-y divide-gray-50">
                        @foreach($recentOrders as $order)
                            <a href="{{ route('account.orders.show', $order) }}" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div>
                                    <div class="font-medium text-gray-900 text-sm">{{ $order->order_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->service->title ?? 'N/A' }} · {{ $order->created_at->diffForHumans() }}</div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                    @match($order->status) {
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'in_progress' => 'bg-blue-50 text-blue-700',
                                        'revision' => 'bg-orange-50 text-orange-700',
                                        'completed' => 'bg-emerald-50 text-emerald-700',
                                        'cancelled' => 'bg-red-50 text-red-700',
                                        default => 'bg-gray-50 text-gray-700',
                                    }">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center text-gray-400 text-sm">
                        No orders yet. <a href="{{ route('services.index') }}" class="text-indigo-600 hover:underline">Browse services</a>
                    </div>
                @endif
            </div>

            {{-- Recent Quotes --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Recent Quotes</h2>
                    <a href="{{ route('account.quotes') }}" class="text-sm text-indigo-600 hover:text-indigo-700">View all →</a>
                </div>
                @if($recentQuotes->count())
                    <div class="divide-y divide-gray-50">
                        @foreach($recentQuotes as $quote)
                            <a href="{{ route('account.quotes.show', $quote) }}" class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                                <div>
                                    <div class="font-medium text-gray-900 text-sm">Quote #{{ $quote->id }}</div>
                                    <div class="text-xs text-gray-500">{{ $quote->service->title ?? 'General' }} · {{ $quote->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                        @match($quote->status) {
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'reviewing' => 'bg-blue-50 text-blue-700',
                                            'quoted' => 'bg-indigo-50 text-indigo-700',
                                            'accepted' => 'bg-emerald-50 text-emerald-700',
                                            'rejected' => 'bg-red-50 text-red-700',
                                            'converted' => 'bg-purple-50 text-purple-700',
                                            default => 'bg-gray-50 text-gray-700',
                                        }">
                                        {{ ucfirst($quote->status) }}
                                    </span>
                                    @if($quote->quoted_price)
                                        <div class="text-xs text-gray-500 mt-1">${{ number_format($quote->quoted_price, 2) }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center text-gray-400 text-sm">
                        No quotes yet. <a href="{{ route('quote.create') }}" class="text-indigo-600 hover:underline">Request a quote</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Notifications --}}
        <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="font-semibold text-gray-900">
                        Notifications
                        @if($unreadNotifications > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">{{ $unreadNotifications }} unread</span>
                        @endif
                    </h2>
                    <a href="{{ route('account.notifications') }}" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">View all →</a>
                </div>
                @if($unreadNotifications > 0)
                    <form method="POST" action="{{ route('account.notifications.read-all') }}">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">Mark all as read</button>
                    </form>
                @endif
            </div>
            @if($notifications->count())
                <div class="divide-y divide-gray-50">
                    @foreach($notifications as $notification)
                        <a href="{{ route('account.notifications.open', $notification) }}" class="px-6 py-4 flex items-start justify-between hover:bg-gray-50 transition-colors">
                            <div>
                                <div class="text-sm font-medium {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900' }}">
                                    @if(!$notification->read_at)
                                        <span class="inline-block w-2 h-2 bg-indigo-500 rounded-full mr-2"></span>
                                    @endif
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-4">{{ $notification->created_at->diffForHumans() }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-8 text-center text-gray-400 text-sm">
                    No notifications yet.
                </div>
            @endif
        </div>

        {{-- Recent Purchases --}}
        @if($recentPurchases->count())
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Recent Purchases</h2>
                    <a href="{{ route('account.purchases') }}" class="text-sm text-indigo-600 hover:text-indigo-700">View all →</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($recentPurchases as $purchase)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900 text-sm">{{ $purchase->product->title ?? 'Product' }}</div>
                                <div class="text-xs text-gray-500">{{ $purchase->purchase_number }} · {{ $purchase->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="text-right">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                    Completed
                                </span>
                                <div class="text-xs text-gray-500 mt-1">${{ number_format($purchase->amount, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
