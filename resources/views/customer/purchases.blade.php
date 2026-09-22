@extends('layouts.app')
@section('title', 'My Purchases')

@section('content')
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <span class="eyebrow">Your Account</span>
        <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">My Purchases</h1>
        <p class="mt-2 text-gray-500">Access your digital product downloads.</p>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($purchases->count())
            <div class="space-y-4">
                @foreach($purchases as $purchase)
                    <div class="surface-card p-6">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="flex items-start gap-4 min-w-0">
                                @if($purchase->product?->featured_image)
                                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $purchase->product->featured_image) }}" alt="{{ $purchase->product->title }}"
                                        class="w-16 h-16 rounded-2xl object-cover border border-surface-200 shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-surface-100 border border-surface-200 flex items-center justify-center shrink-0">
                                        <x-icon name="package" class="w-7 h-7 text-gray-400" />
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-900">{{ $purchase->product->title ?? 'Product' }}</h3>
                                    <div class="text-sm text-gray-500 mt-1 font-mono">{{ $purchase->purchase_number }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $purchase->created_at->format('M d, Y') }}@if($purchase->completed_at) · completed {{ $purchase->completed_at->format('M d, Y') }}@endif</div>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-extrabold text-gray-900">{{ money($purchase->amount) }}</div>
                                <div class="mt-1"><x-status-badge :status="$purchase->status" /></div>
                            </div>
                        </div>

                        {{-- Download Files --}}
                        @if($purchase->status === 'completed' && $purchase->product?->files?->count())
                            <div class="mt-5 pt-5 border-t border-surface-200">
                                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-3">Download Files ({{ $purchase->download_count ?? 0 }} downloads)</div>
                                <div class="space-y-2">
                                    @foreach($purchase->product->files as $file)
                                        <a href="{{ route('account.purchases.download', ['purchase' => $purchase, 'file' => $file]) }}"
                                            class="group flex items-center gap-3 p-3.5 bg-surface-50 border border-surface-200/70 rounded-2xl hover:border-accent-300 hover:bg-accent-50/40 transition-colors">
                                            <span class="w-9 h-9 rounded-xl bg-white border border-surface-200 flex items-center justify-center shrink-0 group-hover:border-accent-300 transition-colors">
                                                <x-icon name="download" class="w-4 h-4 text-accent-700" />
                                            </span>
                                            <span class="text-sm font-semibold text-gray-900 flex-1 min-w-0 truncate">{{ $file->file_name }}</span>
                                            <span class="text-xs text-gray-400 shrink-0">{{ round($file->file_size / 1024) }}KB</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="mt-6">{{ $purchases->links() }}</div>
            </div>
        @else
            <div class="surface-card">
                <x-empty-state
                    icon="cart"
                    title="No purchases yet"
                    description="Browse our digital products to get started.">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
                </x-empty-state>
            </div>
        @endif
    </div>
</section>
@endsection
