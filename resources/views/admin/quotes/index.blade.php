@extends('admin.layouts.app')
@section('page-title', 'Quotes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $quotes->total() }} total quotes</p>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quotes..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="status" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
        <option value="">All Status</option>
        @foreach(['pending','reviewing','quoted','accepted','rejected','expired','converted','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Name</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Service</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($quotes as $quote)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $quote->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $quote->email }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $quote->service->title ?? '-' }}</td>
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
