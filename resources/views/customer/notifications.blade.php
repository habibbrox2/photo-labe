@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<section class="page-hero-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <span class="eyebrow">Your Account</span>
                <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">Notifications</h1>
                <p class="mt-2 text-gray-500">Updates about your quotes, orders, and purchases.</p>
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('account.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Mark all as read</button>
                </form>
            @endif
        </div>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Filter pills --}}
        <div class="flex gap-2 mb-8">
            <a href="{{ route('account.notifications') }}"
                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ !request('filter') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900' }}">
                All ({{ $notifications->total() }})
            </a>
            <a href="{{ route('account.notifications', ['filter' => 'unread']) }}"
                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ request('filter') === 'unread' ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900' }}">
                Unread ({{ $unreadCount }})
            </a>
        </div>

        {{-- List --}}
        @forelse($notifications as $notification)
            <a href="{{ route('account.notifications.open', $notification) }}"
                class="surface-card flex items-start justify-between gap-4 p-5 mb-3 hover:shadow-md transition-shadow {{ $notification->read_at ? 'opacity-70' : '' }}">
                <div class="flex items-start gap-3 min-w-0">
                    @if(!$notification->read_at)
                        <span class="mt-2 w-2.5 h-2.5 bg-accent-500 rounded-full shrink-0"></span>
                    @endif
                    <div class="min-w-0">
                        <div class="font-bold {{ $notification->read_at ? 'text-gray-600' : 'text-gray-900' }}">
                            {{ $notification->data['title'] ?? 'Notification' }}
                        </div>
                        <div class="text-sm text-gray-500 mt-1">{{ $notification->data['message'] ?? '' }}</div>
                        <div class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <span class="text-gray-300 shrink-0 mt-1"><x-icon name="chevron-right" class="w-5 h-5" /></span>
            </a>
        @empty
            <div class="surface-card">
                <x-empty-state
                    icon="bell"
                    title="{{ request('filter') === 'unread' ? 'You are all caught up!' : 'No notifications yet' }}"
                    description="We'll notify you here about new quotes, orders, and updates.">
                    <a href="{{ route('quote.create') }}" class="btn btn-primary">Get a Free Quote</a>
                </x-empty-state>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-6">{{ $notifications->links() }}</div>
        @endif
    </div>
</section>
@endsection
