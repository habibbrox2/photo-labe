@extends('admin.layouts.app')
@section('page-title', 'Quotes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $quotes->total() }} total quotes</p>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quotes..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
    <select name="status" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
        <option value="">All Status</option>
        @foreach(['pending','reviewing','quoted','accepted','rejected','expired','converted','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
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
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $quote->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $quote->status === 'quoted' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $quote->status === 'accepted' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $quote->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                            {{ !in_array($quote->status, ['pending','quoted','accepted','rejected']) ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $quote->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.quotes.show', $quote) }}" class="px-3 py-1 text-xs font-medium text-indigo-600 hover:bg-indigo-50 rounded-lg">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">No quotes found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $quotes->links() }}</div>
@endsection
