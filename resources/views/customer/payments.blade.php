@extends('layouts.app')
@section('title', 'Payment History')

@section('content')
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <span class="eyebrow">Your Account</span>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">Payment History</h1>
        <p class="mt-2 text-gray-500">View all your transactions and invoices.</p>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($payments->count())
            <div class="surface-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-surface-200 bg-surface-50/60">
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Transaction</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Order</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Method</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="text-left px-6 py-3.5 text-[11px] font-bold text-gray-400 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-200/70">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-surface-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm font-medium text-gray-900">{{ $payment->transaction_id ?? $payment->id }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $payment->order->order_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($payment->method ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ money($payment->amount, $payment->currency) }}</td>
                                    <td class="px-6 py-4"><x-status-badge :status="$payment->status" /></td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $payment->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-surface-200">{{ $payments->links() }}</div>
            </div>
        @else
            <div class="surface-card">
                <x-empty-state
                    icon="credit-card"
                    title="No payments yet"
                    description="Your payment history will appear here." />
            </div>
        @endif
    </div>
</section>
@endsection
