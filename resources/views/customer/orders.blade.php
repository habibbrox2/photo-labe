@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <span class="eyebrow">Your Account</span>
                <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">My Orders</h1>
                <p class="mt-2 text-gray-500">Track and manage your service orders.</p>
            </div>
            <a href="{{ route('quote.create') }}" class="btn btn-primary">Request a Quote</a>
        </div>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Filters --}}
        <form method="GET" action="{{ route('account.orders') }}" class="surface-card px-5 py-4 mb-8 flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders..."
                class="form-control-modern flex-1 min-w-[180px] !py-2.5">
            <select name="status" class="form-control-modern w-auto !py-2.5 pr-10">
                <option value="">All Status</option>
                @foreach(['pending', 'in_progress', 'revision', 'completed', 'cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-secondary">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('account.orders') }}" class="btn btn-sm btn-ghost">Clear</a>
            @endif
        </form>

        {{-- Orders Table --}}
        @if($orders->count())
            <div class="surface-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-surface-200 bg-surface-50/60">
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Order</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Service</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-200/70">
                            @foreach($orders as $order)
                                <tr class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('account.orders.show', $order) }}" class="font-mono font-semibold text-gray-900 text-sm hover:text-primary-600 transition-colors">{{ $order->order_number }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->service->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4"><x-status-badge :status="$order->status" /></td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('account.orders.show', $order) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">View <x-icon name="chevron-right" class="w-3.5 h-3.5" /></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-surface-200">{{ $orders->links() }}</div>
            </div>
        @else
            <div class="surface-card">
                <x-empty-state
                    icon="document"
                    title="No orders found"
                    description="You haven't placed any orders yet.">
                    <a href="{{ route('services.index') }}" class="btn btn-primary">Browse Services</a>
                </x-empty-state>
            </div>
        @endif
    </div>
</section>
@endsection
