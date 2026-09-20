@extends('admin.layouts.app')

@section('title', 'Newsletter Subscribers')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Newsletter Subscribers</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $stats['subscribed'] }} active · {{ $stats['unsubscribed'] }} unsubscribed · {{ $stats['total'] }} total</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.newsletter-subscribers.export', ['status' => $status]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-green-600 text-white hover:bg-green-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('admin.newsletter-subscribers.index', ['status' => 'subscribed']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $status === 'subscribed' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Subscribed
            </a>
            <a href="{{ route('admin.newsletter-subscribers.index', ['status' => 'unsubscribed']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $status === 'unsubscribed' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                Unsubscribed
            </a>
            <a href="{{ route('admin.newsletter-subscribers.index', ['status' => 'all']) }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $status === 'all' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                All
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <table class="admin-table min-w-full">
            <thead class="admin-table-head">
                <tr>
                    <th class="admin-th px-6">Email</th>
                    <th class="admin-th px-6">Status</th>
                    <th class="admin-th px-6">IP</th>
                    <th class="admin-th px-6">Subscribed At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subscribers as $subscriber)
                <tr class="hover:bg-gray-50">
                    <td class="admin-td px-6 font-medium text-gray-900">{{ $subscriber->email }}</td>
                    <td class="admin-td px-6">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $subscriber->status === 'subscribed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($subscriber->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $subscriber->ip_address ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $subscriber->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="admin-empty-cell">
                        No subscribers yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $subscribers->links() }}</div>
</div>
@endsection
