@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
                <p class="text-gray-500 mt-1">Updates about your quotes, orders, and purchases.</p>
            </div>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('account.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>

        {{-- Filter Tabs --}}
        <div class="flex gap-2 mb-6">
            <a href="{{ route('account.notifications') }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('filter') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                All ({{ $notifications->total() }})
            </a>
            <a href="{{ route('account.notifications', ['filter' => 'unread']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('filter') === 'unread' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                Unread ({{ $unreadCount }})
            </a>
        </div>

        {{-- List --}}
        @forelse($notifications as $notification)
            <a href="{{ route('account.notifications.open', $notification) }}"
                class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-3 hover:shadow-md transition-shadow {{ $notification->read_at ? 'opacity-70' : '' }}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        @if(!$notification->read_at)
                            <span class="mt-1.5 w-2.5 h-2.5 bg-indigo-500 rounded-full flex-shrink-0"></span>
                        @endif
                        <div>
                            <div class="font-semibold {{ $notification->read_at ? 'text-gray-600' : 'text-gray-900' }}">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">{{ $notification->data['message'] ?? '' }}</div>
                            <div class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-300 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    {{ request('filter') === 'unread' ? 'You are all caught up!' : 'No notifications yet' }}
                </h3>
                <p class="text-gray-500 text-sm mb-4">We'll notify you here about new quotes, orders, and updates.</p>
                <a href="{{ route('quote.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 text-sm">
                    Get a Free Quote
                </a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</section>
@endsection