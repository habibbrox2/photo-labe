@extends('admin.layouts.app')
@section('page-title', 'Notifications')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $notifications->total() }} total notifications</p>
    @if($unreadCount > 0)
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">
                Mark all as read ({{ $unreadCount }})
            </button>
        </form>
    @endif
</div>

{{-- Filter Tabs --}}
<div class="flex gap-2 mb-6">
    <a href="{{ route('admin.notifications.index') }}"
        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ !request('filter') ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        All
    </a>
    <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}"
        class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ request('filter') === 'unread' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
        Unread ({{ $unreadCount }})
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
    @forelse($notifications as $notification)
        <a href="{{ route('admin.notifications.open', $notification) }}" class="px-5 py-4 flex items-start justify-between hover:bg-gray-50 transition-colors">
            <div class="flex items-start gap-3">
                @if(!$notification->read_at)
                    <span class="mt-1.5 w-2.5 h-2.5 bg-indigo-500 rounded-full flex-shrink-0"></span>
                @endif
                <div>
                    <p class="text-sm font-medium {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900' }}">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            <span class="text-xs font-medium {{ $notification->read_at ? 'text-gray-400' : 'text-indigo-600' }} flex-shrink-0 ml-4">
                {{ $notification->read_at ? 'Read' : 'Unread' }}
            </span>
        </a>
    @empty
        <div class="px-5 py-16 text-center">
            <p class="text-gray-400 text-sm">
                {{ request('filter') === 'unread' ? 'No unread notifications.' : 'No notifications yet.' }}
            </p>
        </div>
    @endforelse
</div>

@if($notifications->hasPages())
    <div class="mt-4">{{ $notifications->links() }}</div>
@endif
@endsection