@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Orders</h1>
                <p class="text-gray-500 mt-1">Track and manage your service orders.</p>
            </div>
            <a href="{{ route('quote.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-sm">
                Request a Quote
            </a>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 flex flex-wrap items-center gap-4">
                <form method="GET" action="{{ route('account.orders') }}" class="flex-1 flex items-center gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..."
                        class="flex-1 px-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                    <select name="status" class="px-4 py-2 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                        <option value="">All Status</option>
                        @foreach(['pending', 'in_progress', 'revision', 'completed', 'cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200">Filter</button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('account.orders') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Clear</a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Orders Table --}}
        @if($orders->count())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Service</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-mono font-medium text-gray-900 text-sm">{{ $order->order_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->service->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4">
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
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('account.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">View →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No orders found</h3>
                <p class="text-gray-500 text-sm mb-4">You haven't placed any orders yet.</p>
                <a href="{{ route('services.index') }}" class="inline-block px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 text-sm">
                    Browse Services
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
