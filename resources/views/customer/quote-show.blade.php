@extends('layouts.app')
@section('title', 'Quote #' . $quote->id)

@section('content')
<section class="page-hero-light">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('account.dashboard') }}" class="hover:text-primary-600 transition-colors">Account</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><a href="{{ route('account.quotes') }}" class="hover:text-primary-600 transition-colors">Quotes</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">#{{ $quote->id }}</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="eyebrow">Quote</span>
                <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900">Quote #{{ $quote->id }}</h1>
                <p class="mt-2 text-sm text-gray-500">Submitted {{ $quote->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <x-status-badge :status="$quote->status" class="!text-sm !px-4 !py-2" />
        </div>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-flash class="mb-8" />

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                {{-- Quote Facts --}}
                <div class="surface-card p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Service</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $quote->service->title ?? 'General' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Quantity</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $quote->quantity }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Deadline</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $quote->deadline?->format('M d, Y') ?? 'Flexible' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Quote ID</div>
                            <div class="text-sm font-mono font-semibold text-gray-900">#{{ $quote->id }}</div>
                        </div>
                    </div>
                </div>

                {{-- Requirements --}}
                @if($quote->requirements)
                    <div class="surface-card p-6">
                        <h3 class="font-bold text-gray-900 mb-3">Your Requirements</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $quote->requirements }}</p>
                    </div>
                @endif

                {{-- Files --}}
                @if($quote->files->count())
                    <div class="surface-card p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Uploaded Files</h3>
                        <div class="space-y-2">
                            @foreach($quote->files as $file)
                                <div class="flex items-center gap-3 p-3.5 bg-surface-50 border border-surface-200/70 rounded-2xl">
                                    <span class="w-9 h-9 rounded-xl bg-white border border-surface-200 flex items-center justify-center shrink-0">
                                        <x-icon name="document" class="w-4.5 h-4.5 text-gray-500" />
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 flex-1 min-w-0 truncate">{{ $file->original_name }}</span>
                                    <span class="text-xs text-gray-400 shrink-0">{{ round($file->file_size / 1024) }}KB</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Pricing --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Pricing</h3>
                    @if($quote->quoted_price)
                        <div class="text-center mb-5">
                            <div class="text-4xl font-extrabold text-gray-900">${{ number_format($quote->quoted_price, 2) }}</div>
                            <div class="text-sm text-gray-500 mt-1">Quoted price</div>
                        </div>

                        @if($quote->status === 'quoted')
                            <div class="space-y-2">
                                <form method="POST" action="{{ route('account.quotes.accept', $quote) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-full">Accept &amp; Create Order</button>
                                </form>
                                <form method="POST" action="{{ route('account.quotes.reject', $quote) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary w-full">Reject Quote</button>
                                </form>
                            </div>
                        @elseif($quote->status === 'converted')
                            @if($quote->order)
                                <a href="{{ route('account.orders.show', $quote->order) }}" class="btn btn-primary w-full">View Order <x-icon name="arrow-right" class="w-4 h-4" /></a>
                            @endif
                        @elseif($quote->status === 'accepted')
                            <p class="text-sm text-emerald-600 text-center font-semibold inline-flex items-center gap-1.5 w-full justify-center"><x-icon name="check" class="w-4 h-4" /> Quote accepted — order being created</p>
                        @elseif($quote->status === 'rejected')
                            <p class="text-sm text-red-600 text-center font-semibold">Quote was rejected</p>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <div class="text-3xl font-extrabold text-surface-300">—</div>
                            <div class="text-sm text-gray-500 mt-1">Awaiting pricing from our team</div>
                        </div>
                        @if(in_array($quote->status, ['pending', 'reviewing']))
                            <p class="text-xs text-gray-400 text-center mt-3">We're reviewing your request and will send pricing soon.</p>
                        @endif
                    @endif
                </div>

                {{-- Quote Details --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Status</dt>
                            <dd class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $quote->status)) }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Submitted</dt>
                            <dd class="font-medium text-gray-900">{{ $quote->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Updated</dt>
                            <dd class="font-medium text-gray-900">{{ $quote->updated_at->format('M d, Y') }}</dd>
                        </div>
                        @if($quote->admin_notes)
                            <div class="pt-3 border-t border-surface-200">
                                <dt class="text-gray-500 text-xs font-bold uppercase tracking-wider">Studio Notes</dt>
                                <dd class="text-gray-700 mt-1">{{ $quote->admin_notes }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>

                {{-- Contact --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-2">Need Help?</h3>
                    <p class="text-sm text-gray-500 mb-4">Have questions about your quote? Get in touch.</p>
                    <a href="{{ route('contact') }}" class="btn btn-secondary w-full">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
