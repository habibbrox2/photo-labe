@extends('layouts.app')
@section('title', 'My Quotes')

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Quotes</h1>
                <p class="text-gray-500 mt-1">Track your quote requests and pricing.</p>
            </div>
            <a href="{{ route('quote.create') }}" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition-colors text-sm">
                New Quote
            </a>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="px-6 py-4 flex flex-wrap items-center gap-4">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('account.quotes') }}" class="px-3 py-1.5 rounded-full text-xs font-medium {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
                    @foreach(['pending', 'reviewing', 'quoted', 'accepted', 'rejected', 'converted'] as $s)
                        <a href="{{ route('account.quotes', ['status' => $s]) }}" class="px-3 py-1.5 rounded-full text-xs font-medium {{ request('status') === $s ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ ucfirst($s) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quotes List --}}
        @if($quotes->count())
            <div class="space-y-4">
                @foreach($quotes as $quote)
                    <a href="{{ route('account.quotes.show', $quote) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="font-semibold text-gray-900">Quote #{{ $quote->id }}</h3>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                        @match($quote->status) {
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'reviewing' => 'bg-blue-50 text-blue-700',
                                            'quoted' => 'bg-indigo-50 text-indigo-700',
                                            'accepted' => 'bg-emerald-50 text-emerald-700',
                                            'rejected' => 'bg-red-50 text-red-700',
                                            'converted' => 'bg-purple-50 text-purple-700',
                                            'expired' => 'bg-gray-50 text-gray-500',
                                            default => 'bg-gray-50 text-gray-700',
                                        }">
                                        {{ ucfirst($quote->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500 mb-2">
                                    Service: <span class="text-gray-700 font-medium">{{ $quote->service->title ?? 'General' }}</span>
                                    · Qty: {{ $quote->quantity }}
                                    · {{ $quote->created_at->format('M d, Y') }}
                                </div>
                                @if($quote->requirements)
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $quote->requirements }}</p>
                                @endif
                            </div>
                            <div class="text-right ml-6">
                                @if($quote->quoted_price)
                                    <div class="text-2xl font-bold text-indigo-600">${{ number_format($quote->quoted_price, 2) }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Quoted Price</div>
                                @else
                                    <div class="text-sm text-gray-400">Awaiting quote</div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons for Quoted Status --}}
                        @if($quote->status === 'quoted')
                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center gap-3">
                                <form method="POST" action="{{ route('account.quotes.accept', $quote) }}">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 text-sm">
                                        Accept & Create Order
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('account.quotes.reject', $quote) }}">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 border border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 text-sm">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </a>
                @endforeach

                <div class="mt-6">
                    {{ $quotes->links() }}
                </div>
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">No quotes yet</h3>
                <p class="text-gray-500 text-sm mb-4">Request a quote to get started with our services.</p>
                <a href="{{ route('quote.create') }}" class="inline-block px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 text-sm">
                    Request a Quote
                </a>
            </div>
        @endif
    </div>
</section>
@endsection
