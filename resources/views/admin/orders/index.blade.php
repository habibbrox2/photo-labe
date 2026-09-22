@extends('admin.layouts.app')
@section('page-title', 'Orders')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $orders->total() }} total orders</p>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="status" class="admin-filter-select">
        <option value="">All Status</option>
        @foreach(['pending','confirmed','paid','processing','quality_check','revision','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
        @endforeach
    </select>
    <button type="submit" class="admin-filter-btn">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="admin-table min-w-[640px]">
        <thead class="admin-table-head">
            <tr>
                <th class="admin-th">Order #</th>
                <th class="admin-th">Customer</th>
                <th class="admin-th">Total</th>
                <th class="admin-th">Status</th>
                <th class="admin-th">Date</th>
                <th class="admin-th text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono font-medium text-gray-900 text-xs">{{ $order->order_number }}</td>
                    <td class="admin-td text-gray-500">{{ $order->user->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-gray-900 font-medium">{{ money($order->total, $order->currency) }}</td>
                    <td class="px-5 py-3">
                        <x-status-badge :status="$order->status" />
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><x-empty-state icon="cart" title="No orders found" description="Orders created from quotes or checkout will show up here."></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
