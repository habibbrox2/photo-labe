@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    
    <div>
        <span class="eyebrow">Studio Overview</span>
        <p class="mt-3 text-sm text-gray-500">Welcome back, {{ auth()->user()->name }} — here's what's happening today.</p>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="surface-card p-5 border-accent-200/70">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1.5">${{ number_format($stats['revenue'], 2) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">completed orders</p>
                </div>
                <span class="w-11 h-11 shrink-0 rounded-2xl bg-accent-500 flex items-center justify-center shadow-sm shadow-accent-500/25">
                    <x-icon name="credit-card" class="w-5 h-5 text-gray-900" />
                </span>
            </div>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Orders</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1.5">{{ $stats['orders'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">all time</p>
                </div>
                <span class="w-11 h-11 shrink-0 rounded-2xl bg-surface-100 border border-surface-200 flex items-center justify-center">
                    <x-icon name="cart" class="w-5 h-5 text-gray-600" />
                </span>
            </div>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Pending Quotes</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1.5">{{ $stats['pending_quotes'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">awaiting your review</p>
                </div>
                <span class="w-11 h-11 shrink-0 rounded-2xl bg-surface-100 border border-surface-200 flex items-center justify-center">
                    <x-icon name="document" class="w-5 h-5 text-gray-600" />
                </span>
            </div>
        </div>

        <div class="surface-card p-5">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Customers</p>
                    <p class="text-2xl font-extrabold text-gray-900 mt-1.5">{{ $stats['customers'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">registered accounts</p>
                </div>
                <span class="w-11 h-11 shrink-0 rounded-2xl bg-surface-100 border border-surface-200 flex items-center justify-center">
                    <x-icon name="user" class="w-5 h-5 text-gray-600" />
                </span>
            </div>
        </div>
    </div>

    
    <div class="surface-card px-5 py-4 flex flex-wrap items-center gap-x-10 gap-y-3">
        <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 shrink-0">Content library</p>
        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-2 text-sm group">
            <span class="font-extrabold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $stats['services'] }}</span>
            <span class="text-gray-500">services</span>
        </a>
        <a href="{{ route('admin.portfolio.index') }}" class="flex items-center gap-2 text-sm group">
            <span class="font-extrabold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $stats['portfolio'] }}</span>
            <span class="text-gray-500">portfolio projects</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-sm group">
            <span class="font-extrabold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $stats['products'] }}</span>
            <span class="text-gray-500">products</span>
        </a>
    </div>

    
    <div class="surface-card overflow-hidden">
        <div class="px-5 py-4 border-b border-surface-200 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    Notifications
                    @if($unreadNotifications > 0)
                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-accent-500 text-gray-900">{{ $unreadNotifications }} new</span>
                    @endif
                </h3>
                <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">View all</a>
            </div>
            @if($unreadNotifications > 0)
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-gray-900">Mark all as read</button>
                </form>
            @endif
        </div>
        <div class="divide-y divide-surface-200/70">
            @php$__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; @endphp
                <a href="{{ route('admin.notifications.open', $notification) }}" class="px-5 py-3.5 flex items-start justify-between gap-4 hover:bg-surface-50 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold flex items-center gap-2 {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900' }}">
                            @if(!$notification->read_at)
                                <span class="w-2 h-2 bg-accent-500 rounded-full shrink-0"></span>
                            @endif
                            {{ $notification->data['title'] ?? 'Notification' }}

                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap shrink-0">{{ $notification->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <div class="px-5 py-8 text-center text-sm text-gray-400">No notifications yet.</div>
            @endif
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="surface-card overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Recent Quotes</h3>
                <a href="{{ route('admin.quotes.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">All quotes</a>
            </div>
            <div class="divide-y divide-surface-200/70">
                @php$__empty_1 = true; $__currentLoopData = $recentQuotes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quote): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; @endphp
                    <a href="{{ route('admin.quotes.show', $quote) }}" class="px-5 py-3.5 flex items-center justify-between gap-4 hover:bg-surface-50 transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $quote->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $quote->email }} · {{ $quote->created_at->diffForHumans() }}</p>
                        </div>
                        <x-status-badge :status="{{ \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($quote->status) }}" />
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No quotes yet.</div>
                @endif
            </div>
        </div>

        <div class="surface-card overflow-hidden">
            <div class="px-5 py-4 border-b border-surface-200 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-primary-600 hover:text-primary-700">All orders</a>
            </div>
            <div class="divide-y divide-surface-200/70">
                @php$__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; @endphp
                    <a href="{{ route('admin.orders.show', $order) }}" class="px-5 py-3.5 flex items-center justify-between gap-4 hover:bg-surface-50 transition-colors">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900 font-mono">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $order->user->name ?? 'N/A' }} · {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <x-status-badge :status="{{ \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order->status) }}" />
                    </a>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-400">No orders yet.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

