@extends('layouts.app')
@section('title', 'My Dashboard')

@section('content')
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <span class="eyebrow">Your Account</span>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="mt-2 text-gray-500">Here's an overview of your orders, quotes and downloads.</p>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="surface-card p-5">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-accent-100 flex items-center justify-center shrink-0">
                        <x-icon name="clock" class="w-5 h-5 text-accent-700" />
                    </span>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['active_orders'] }}</div>
                        <div class="mt-1 text-xs font-medium text-gray-500">Active orders</div>
                    </div>
                </div>
            </div>
            <div class="surface-card p-5">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-surface-100 border border-surface-200 flex items-center justify-center shrink-0">
                        <x-icon name="document" class="w-5 h-5 text-gray-600" />
                    </span>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['pending_quotes'] }}</div>
                        <div class="mt-1 text-xs font-medium text-gray-500">Pending quotes</div>
                    </div>
                </div>
            </div>
            <div class="surface-card p-5">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                        <x-icon name="check" class="w-5 h-5 text-emerald-600" />
                    </span>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ $stats['completed_orders'] }}</div>
                        <div class="mt-1 text-xs font-medium text-gray-500">Completed</div>
                    </div>
                </div>
            </div>
            <div class="surface-card p-5">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-surface-100 border border-surface-200 flex items-center justify-center shrink-0">
                        <x-icon name="credit-card" class="w-5 h-5 text-gray-600" />
                    </span>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ money($stats['total_spent']) }}</div>
                        <div class="mt-1 text-xs font-medium text-gray-500">Total spent</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <a href="{{ route('quote.create') }}" class="btn btn-primary p-4 justify-start gap-3">
                <x-icon name="plus" class="w-5 h-5" />
                <span class="text-sm">New Quote</span>
            </a>
            <a href="{{ route('products.index') }}" class="surface-card p-4 flex items-center gap-3 hover:border-gray-300 hover:shadow-md transition-all">
                <span class="w-8 h-8 rounded-lg bg-surface-100 border border-surface-200 flex items-center justify-center shrink-0">
                    <x-icon name="cart" class="w-4 h-4 text-gray-600" />
                </span>
                <span class="font-semibold text-sm text-gray-900">Shop</span>
            </a>
            <a href="{{ route('account.orders') }}" class="surface-card p-4 flex items-center gap-3 hover:border-gray-300 hover:shadow-md transition-all">
                <span class="w-8 h-8 rounded-lg bg-surface-100 border border-surface-200 flex items-center justify-center shrink-0">
                    <x-icon name="package" class="w-4 h-4 text-gray-600" />
                </span>
                <span class="font-semibold text-sm text-gray-900">My Orders</span>
            </a>
            <a href="{{ route('contact') }}" class="surface-card p-4 flex items-center gap-3 hover:border-gray-300 hover:shadow-md transition-all">
                <span class="w-8 h-8 rounded-lg bg-surface-100 border border-surface-200 flex items-center justify-center shrink-0">
                    <x-icon name="chat" class="w-4 h-4 text-gray-600" />
                </span>
                <span class="font-semibold text-sm text-gray-900">Contact</span>
            </a>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mt-6">
            {{-- Recent Orders --}}
            <div class="surface-card overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-200 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Recent Orders</h2>
                    <a href="{{ route('account.orders') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">View all <x-icon name="arrow-right" class="w-3.5 h-3.5" /></a>
                </div>
                @if($recentOrders->count())
                    <div class="divide-y divide-surface-200/70">
                        @foreach($recentOrders as $order)
                            <a href="{{ route('account.orders.show', $order) }}" class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-surface-50 transition-colors">
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 text-sm">{{ $order->order_number }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $order->service->title ?? 'N/A' }} · {{ $order->created_at->diffForHumans() }}</div>
                                </div>
                                <x-status-badge :status="$order->status" />
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-gray-400">No orders yet.</p>
                        <a href="{{ route('services.index') }}" class="mt-2 inline-block text-sm font-semibold text-accent-700 hover:text-accent-800">Browse services</a>
                    </div>
                @endif
            </div>

            {{-- Recent Quotes --}}
            <div class="surface-card overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-200 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Recent Quotes</h2>
                    <a href="{{ route('account.quotes') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">View all <x-icon name="arrow-right" class="w-3.5 h-3.5" /></a>
                </div>
                @if($recentQuotes->count())
                    <div class="divide-y divide-surface-200/70">
                        @foreach($recentQuotes as $quote)
                            <a href="{{ route('account.quotes.show', $quote) }}" class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-surface-50 transition-colors">
                                <div class="min-w-0">
                                    <div class="font-semibold text-gray-900 text-sm">Quote #{{ $quote->id }}</div>
                                    <div class="text-xs text-gray-500 truncate">{{ $quote->service->title ?? 'General' }} · {{ $quote->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-right shrink-0">
                                    <x-status-badge :status="$quote->status" />
                                    @if($quote->quoted_price)
                                        <div class="text-xs text-gray-500 mt-1">{{ money($quote->quoted_price) }}</div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-gray-400">No quotes yet.</p>
                        <a href="{{ route('quote.create') }}" class="mt-2 inline-block text-sm font-semibold text-accent-700 hover:text-accent-800">Request a quote</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Notifications --}}
        <div class="surface-card overflow-hidden mt-6">
            <div class="px-6 py-4 border-b border-surface-200 flex items-center justify-between gap-4">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">
                    Notifications
                    @if($unreadNotifications > 0)
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-accent-500 text-gray-900">{{ $unreadNotifications }} new</span>
                    @endif
                </h2>
                <div class="flex items-center gap-4">
                    <a href="{{ route('account.notifications') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">View all</a>
                    @if($unreadNotifications > 0)
                        <form method="POST" action="{{ route('account.notifications.read-all') }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-gray-800">Mark all as read</button>
                        </form>
                    @endif
                </div>
            </div>
            @if($notifications->count())
                <div class="divide-y divide-surface-200/70">
                    @foreach($notifications as $notification)
                        <a href="{{ route('account.notifications.open', $notification) }}" class="px-6 py-4 flex items-start justify-between gap-4 hover:bg-surface-50 transition-colors">
                            <div class="min-w-0">
                                <div class="text-sm font-semibold flex items-center gap-2 {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900' }}">
                                    @if(!$notification->read_at)
                                        <span class="w-2 h-2 bg-accent-500 rounded-full shrink-0"></span>
                                    @endif
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-10 text-center text-sm text-gray-400">No notifications yet.</div>
            @endif
        </div>

        {{-- Recent Purchases --}}
        @if($recentPurchases->count())
            <div class="surface-card overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-surface-200 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900">Recent Purchases</h2>
                    <a href="{{ route('account.purchases') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">View all <x-icon name="arrow-right" class="w-3.5 h-3.5" /></a>
                </div>
                <div class="divide-y divide-surface-200/70">
                    @foreach($recentPurchases as $purchase)
                        <div class="px-6 py-4 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-900 text-sm truncate">{{ $purchase->product->title ?? 'Product' }}</div>
                                <div class="text-xs text-gray-500">{{ $purchase->purchase_number }} · {{ $purchase->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/10">Completed</span>
                                <div class="text-xs text-gray-500 mt-1">{{ money($purchase->amount) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
