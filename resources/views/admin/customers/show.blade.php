@extends('admin.layouts.app')
@section('page-title', 'Customer Detail')

@section('content')
<div class="max-w-4xl">
    <x-breadcrumbs :items="[['label' => 'Customers', 'url' => route('admin.customers.index')], ['label' => $customer->name]]" />

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center text-primary-600 text-2xl font-bold">{{ substr($customer->name, 0, 1) }}</div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h2>
                <p class="text-gray-500">{{ $customer->email }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-500 block">Phone</span><span class="font-medium">{{ $customer->phone ?? '-' }}</span></div>
            <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-500 block">Orders</span><span class="font-medium">{{ $customer->orders->count() }}</span></div>
            <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-500 block">Quotes</span><span class="font-medium">{{ $customer->quotes->count() }}</span></div>
            <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-500 block">Joined</span><span class="font-medium">{{ $customer->created_at->format('M d, Y') }}</span></div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Recent Orders</h3>
        @if($customer->orders->count())
            <div class="space-y-3">
                @foreach($customer->orders->take(10) as $order)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <span class="font-mono text-sm font-medium">{{ $order->order_number }}</span>
                            <span class="text-gray-500 text-sm ml-2">${{ number_format($order->total, 2) }}</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($order->status) }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-400 text-sm">No orders yet.</p>
        @endif
    </div>
</div>
@endsection
