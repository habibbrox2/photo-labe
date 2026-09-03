@extends('layouts.app')
@section('title', 'My Purchases')

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Purchases</h1>
            <p class="text-gray-500 mt-1">Access your digital product downloads.</p>
        </div>

        @if($purchases->count())
            <div class="space-y-4">
                @foreach($purchases as $purchase)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                @if($purchase->product?->featured_image)
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $purchase->product->featured_image) }}" alt="{{ $purchase->product->title }}"
                                        class="w-16 h-16 rounded-xl object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-primary-50 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $purchase->product->title ?? 'Product' }}</h3>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $purchase->purchase_number }} · {{ $purchase->created_at->format('M d, Y') }}
                                    </div>
                                    @if($purchase->completed_at)
                                        <div class="text-xs text-gray-400 mt-1">Completed {{ $purchase->completed_at->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-gray-900">${{ number_format($purchase->amount, 2) }}</div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 mt-1 inline-block">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Download Files --}}
                        @if($purchase->status === 'completed' && $purchase->product?->files?->count())
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <div class="text-xs text-gray-500 mb-2">Download Files ({{ $purchase->download_count ?? 0 }} downloads)</div>
                                <div class="space-y-2">
                                    @foreach($purchase->product->files as $file)
                                        <a href="{{ route('account.purchases.download', ['purchase' => $purchase, 'file' => $file]) }}"
                                            class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-primary-50 transition-colors group">
                                            <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center group-hover:border-primary-300">
                                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $file->file_name }}</span>
                                            <span class="text-xs text-gray-400">{{ round($file->file_size / 1024) }}KB</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="mt-6">
                    {{ $purchases->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No purchases yet</h3>
                <p class="text-gray-500 text-sm mb-4">Browse our digital products to get started.</p>
                <a href="{{ route('products.index') }}" class="inline-block px-5 py-2.5 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 text-sm">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
