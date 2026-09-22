@extends('layouts.app')
@section('title', 'My Quotes')

@section('content')
<section class="page-hero-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="flex flex-wrap items-end justify-between gap-6">
            <div>
                <span class="eyebrow">Your Account</span>
                <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">My Quotes</h1>
                <p class="mt-2 text-gray-500">Track your quote requests and pricing.</p>
            </div>
            <a href="{{ route('quote.create') }}" class="btn btn-primary">New Quote</a>
        </div>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Filter pills --}}
        <div class="flex flex-wrap gap-2 mb-8">
            <a href="{{ route('account.quotes') }}"
                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ !request('status') ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900' }}">All</a>
            @foreach(['pending', 'reviewing', 'quoted', 'accepted', 'rejected', 'converted'] as $s)
                <a href="{{ route('account.quotes', ['status' => $s]) }}"
                    class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ request('status') === $s ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-surface-200 hover:border-gray-300 hover:text-gray-900' }}">
                    {{ ucfirst($s) }}
                </a>
            @endforeach
        </div>

        {{-- Quotes List --}}
        @if($quotes->count())
            <div class="space-y-4">
                @foreach($quotes as $quote)
                    <div class="surface-card p-6 hover:shadow-md transition-shadow">
                        <a href="{{ route('account.quotes.show', $quote) }}" class="block">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="flex-1 min-w-[240px]">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h3 class="font-bold text-gray-900">Quote #{{ $quote->id }}</h3>
                                        <x-status-badge :status="$quote->status" />
                                    </div>
                                    <div class="text-sm text-gray-500 mb-2">
                                        {{ $quote->service->title ?? 'General' }}
                                        · Qty {{ $quote->quantity }}
                                        · {{ $quote->created_at->format('M d, Y') }}
                                    </div>
                                    @if($quote->requirements)
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $quote->requirements }}</p>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    @if($quote->quoted_price)
                                        <div class="text-2xl font-extrabold text-gray-900">{{ money($quote->quoted_price) }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Quoted price</div>
                                    @else
                                        <div class="text-sm text-gray-400">Awaiting quote</div>
                                    @endif
                                </div>
                            </div>
                        </a>

                        {{-- Accept / reject for quoted status --}}
                        @if($quote->status === 'quoted')
                            <div class="mt-5 pt-5 border-t border-surface-200 flex flex-wrap items-center gap-3">
                                <form method="POST" action="{{ route('account.quotes.accept', $quote) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Accept &amp; Create Order</button>
                                </form>
                                <form method="POST" action="{{ route('account.quotes.reject', $quote) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-secondary">Reject</button>
                                </form>
                                <a href="{{ route('account.quotes.show', $quote) }}" class="ml-auto inline-flex items-center gap-1 text-sm font-semibold text-primary-600 hover:text-primary-700">Details <x-icon name="chevron-right" class="w-3.5 h-3.5" /></a>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="mt-6">{{ $quotes->links() }}</div>
            </div>
        @else
            <div class="surface-card">
                <x-empty-state
                    icon="document"
                    title="No quotes yet"
                    description="Request a quote to get started with our services.">
                    <a href="{{ route('quote.create') }}" class="btn btn-primary">Request a Quote</a>
                </x-empty-state>
            </div>
        @endif
    </div>
</section>
@endsection
