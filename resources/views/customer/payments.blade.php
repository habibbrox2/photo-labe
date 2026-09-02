@extends('layouts.app')
@section('title', 'Payment History')

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Payment History</h1>
            <p class="text-gray-500 mt-1">View all your transactions and invoices.</p>
        </div>

        @if($payments->count())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Transaction</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Order</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Method</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Amount</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-mono text-sm text-gray-900">{{ $payment->transaction_id ?? $payment->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $payment->order->order_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($payment->method ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                            @match($payment->status) {
                                                'completed' => 'bg-emerald-50 text-emerald-700',
                                                'pending' => 'bg-amber-50 text-amber-700',
                                                'failed' => 'bg-red-50 text-red-700',
                                                'refunded' => 'bg-purple-50 text-purple-700',
                                                default => 'bg-gray-50 text-gray-700',
                                            }">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $payments->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No payments yet</h3>
                <p class="text-gray-500 text-sm">Your payment history will appear here.</p>
            </div>
        @endif
    </div>
</section>
@endsection
