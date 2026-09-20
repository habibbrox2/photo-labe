@extends('admin.layouts.app')
@section('page-title', 'Quotes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $quotes->total() }} total quotes</p>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quotes..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="status" class="admin-filter-select">
        <option value="">All Status</option>
        @foreach(['pending','reviewing','quoted','accepted','rejected','expired','converted','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="admin-filter-btn">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="admin-table min-w-[640px]">
        <thead class="admin-table-head">
            <tr>
                <th class="admin-th">Name</th>
                <th class="admin-th">Email</th>
                <th class="admin-th">Service</th>
                <th class="admin-th">Status</th>
                <th class="admin-th">Date</th>
                <th class="admin-th text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($quotes as $quote)
                <tr class="hover:bg-gray-50">
                    <td class="admin-td font-medium text-gray-900">{{ $quote->name }}</td>
                    <td class="admin-td text-gray-500">{{ $quote->email }}</td>
                    <td class="admin-td text-gray-500">{{ $quote->service->title ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <x-status-badge :status="$quote->status" />
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $quote->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.quotes.show', $quote) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><x-empty-state icon="quote" title="No quotes found" description="New quote requests from customers will appear here."></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $quotes->links() }}</div>
@endsection
