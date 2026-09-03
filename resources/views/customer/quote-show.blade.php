@extends('layouts.app')
@section('title', 'Quote #' . $quote->id)

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('account.quotes') }}" class="text-sm text-primary-600 hover:text-primary-700 mb-6 inline-block">← Back to Quotes</a>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Quote Header --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Quote #{{ $quote->id }}</h1>
                            <p class="text-gray-500 text-sm">Submitted {{ $quote->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-medium
                            @match($quote->status) {
                                'pending' => 'bg-amber-50 text-amber-700',
                                'reviewing' => 'bg-blue-50 text-blue-700',
                                'quoted' => 'bg-primary-50 text-primary-700',
                                'accepted' => 'bg-emerald-50 text-emerald-700',
                                'rejected' => 'bg-red-50 text-red-700',
                                'converted' => 'bg-accent-50 text-accent-700',
                                'expired' => 'bg-gray-50 text-gray-500',
                                default => 'bg-gray-50 text-gray-700',
                            }">
                            {{ ucfirst($quote->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Service</div>
                            <div class="text-sm font-medium text-gray-900">{{ $quote->service->title ?? 'General' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Quantity</div>
                            <div class="text-sm font-medium text-gray-900">{{ $quote->quantity }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Deadline</div>
                            <div class="text-sm font-medium text-gray-900">{{ $quote->deadline?->format('M d, Y') ?? 'Flexible' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Quote ID</div>
                            <div class="text-sm font-mono text-gray-900">#{{ $quote->id }}</div>
                        </div>
                    </div>
                </div>

                {{-- Requirements --}}
                @if($quote->requirements)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Your Requirements</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $quote->requirements }}</p>
                    </div>
                @endif

                {{-- Files --}}
                @if($quote->files->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Uploaded Files</h3>
                        <div class="space-y-2">
                            @foreach($quote->files as $file)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="text-sm text-gray-700 flex-1">{{ $file->original_name }}</span>
                                    <span class="text-xs text-gray-400">{{ round($file->file_size / 1024) }}KB</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Pricing --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Pricing</h3>
                    @if($quote->quoted_price)
                        <div class="text-center mb-4">
                            <div class="text-3xl font-bold text-primary-600">${{ number_format($quote->quoted_price, 2) }}</div>
                            <div class="text-sm text-gray-500 mt-1">Quoted Price</div>
                        </div>

                        @if($quote->status === 'quoted')
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('account.quotes.accept', $quote) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 text-sm">
                                        ✅ Accept & Create Order
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('account.quotes.reject', $quote) }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 border border-gray-200 text-gray-600 font-semibold rounded-xl hover:bg-gray-50 text-sm">
                                        Reject Quote
                                    </button>
                                </form>
                            </div>
                        @elseif($quote->status === 'converted')
                            @if($quote->order)
                                <a href="{{ route('account.orders.show', $quote->order) }}" class="block w-full text-center px-4 py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 text-sm">
                                    View Order →
                                </a>
                            @endif
                        @elseif($quote->status === 'accepted')
                            <p class="text-sm text-emerald-600 text-center font-medium">✓ Quote accepted — order being created</p>
                        @elseif($quote->status === 'rejected')
                            <p class="text-sm text-red-600 text-center font-medium">Quote was rejected</p>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <div class="text-2xl font-bold text-gray-300">—</div>
                            <div class="text-sm text-gray-500 mt-1">Awaiting pricing from our team</div>
                        </div>
                        @if(in_array($quote->status, ['pending', 'reviewing']))
                            <p class="text-xs text-gray-400 text-center mt-3">We're reviewing your request and will send pricing soon.</p>
                        @endif
                    @endif
                </div>

                {{-- Quote Details --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Details</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $quote->status)) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Submitted</span>
                            <span class="text-gray-900">{{ $quote->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Updated</span>
                            <span class="text-gray-900">{{ $quote->updated_at->format('M d, Y') }}</span>
                        </div>
                        @if($quote->admin_notes)
                            <div class="pt-3 border-t border-gray-100">
                                <span class="text-gray-500 text-xs">Admin Notes</span>
                                <p class="text-gray-700 mt-1">{{ $quote->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Contact --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-3">Need Help?</h3>
                    <p class="text-sm text-gray-500 mb-3">Have questions about your quote? Get in touch.</p>
                    <a href="{{ route('contact') }}" class="block w-full text-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
