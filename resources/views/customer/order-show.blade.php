@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@section('content')
<section class="page-hero-light">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <nav aria-label="Breadcrumb">
            <ol class="flex items-center gap-1.5 text-sm text-gray-500">
                <li><a href="{{ route('account.dashboard') }}" class="hover:text-primary-600 transition-colors">Account</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li><a href="{{ route('account.orders') }}" class="hover:text-primary-600 transition-colors">Orders</a></li>
                <li aria-hidden="true" class="text-gray-300">/</li>
                <li aria-current="page" class="text-gray-700 font-medium">{{ $order->order_number }}</li>
            </ol>
        </nav>
    </div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="eyebrow">Order</span>
                <h1 class="mt-4 text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 font-mono">{{ $order->order_number }}</h1>
                <p class="mt-2 text-sm text-gray-500">Placed {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <x-status-badge :status="$order->status" class="!text-sm !px-4 !py-2" />
        </div>
    </div>
</section>

<section class="py-12 lg:py-14 bg-white min-h-[60vh]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-flash class="mb-8" />

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order Facts --}}
                <div class="surface-card p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Service</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $order->service->title ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Quantity</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $order->quantity }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Deadline</div>
                            <div class="text-sm font-semibold text-gray-900">{{ $order->deadline?->format('M d, Y') ?? 'Flexible' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Total</div>
                            <div class="text-sm font-extrabold text-gray-900">${{ number_format($order->total, 2) }}</div>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-5 pt-5 border-t border-surface-200">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Your Notes</div>
                            <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                        </div>
                    @endif

                    @if($order->admin_notes)
                        <div class="mt-5 pt-5 border-t border-surface-200">
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Studio Notes</div>
                            <p class="text-sm text-gray-700">{{ $order->admin_notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Order Progress Timeline --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-5">Order Progress</h3>
                    @php
                        $steps = [
                            'pending' => 'Order Placed',
                            'in_progress' => 'In Progress',
                            'revision' => 'Revision',
                            'completed' => 'Completed',
                        ];
                        $statusOrder = array_keys($steps);
                        $currentIndex = array_search($order->status, $statusOrder);
                        if ($currentIndex === false) $currentIndex = -1;
                    @endphp

                    <ol class="relative space-y-5">
                        @foreach($steps as $key => $label)
                            @php
                                $stepIndex = array_search($key, $statusOrder);
                                $isCompleted = $stepIndex <= $currentIndex;
                                $isCurrent = $key === $order->status;
                            @endphp
                            <li class="flex items-center gap-4">
                                <span class="w-9 h-9 shrink-0 rounded-full flex items-center justify-center
                                    {{ $isCurrent ? 'bg-accent-500 text-gray-900' : ($isCompleted ? 'bg-emerald-50 text-emerald-600' : 'bg-surface-100 text-gray-300 border border-surface-200') }}">
                                    @if($isCurrent)
                                        <span class="w-2.5 h-2.5 rounded-full bg-gray-900"></span>
                                    @elseif($isCompleted)
                                        <x-icon name="check" class="w-4 h-4" />
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-current"></span>
                                    @endif
                                </span>
                                <span class="flex-1 text-sm {{ $isCurrent ? 'font-bold text-gray-900' : ($isCompleted ? 'font-semibold text-gray-700' : 'text-gray-400') }}">{{ $label }}</span>
                                @if($isCurrent)
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-accent-700 bg-accent-100 rounded-full px-2.5 py-1">Current</span>
                                @elseif($isCompleted)
                                    <span class="text-xs text-gray-400">Done</span>
                                @endif
                            </li>
                        @endforeach

                        @if($order->status === 'cancelled')
                            <li class="flex items-center gap-4">
                                <span class="w-9 h-9 shrink-0 rounded-full bg-red-50 text-red-500 flex items-center justify-center">
                                    <x-icon name="x" class="w-4 h-4" />
                                </span>
                                <span class="flex-1 text-sm font-bold text-red-600">Cancelled</span>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-red-700 bg-red-50 rounded-full px-2.5 py-1">Current</span>
                            </li>
                        @endif
                    </ol>
                </div>

                {{-- Files --}}
                @if($order->files->count())
                    <div class="surface-card p-6">
                        <h3 class="font-bold text-gray-900 mb-4">Files</h3>
                        <div class="space-y-3">
                            @foreach($order->files as $file)
                                <div class="flex items-center gap-3 p-3.5 bg-surface-50 border border-surface-200/70 rounded-2xl">
                                    <div class="w-10 h-10 rounded-xl bg-white border border-surface-200 flex items-center justify-center shrink-0">
                                        @if(str_starts_with($file->mime_type, 'image/'))
                                            <x-icon name="image" class="w-5 h-5 text-accent-600" />
                                        @else
                                            <x-icon name="document" class="w-5 h-5 text-gray-500" />
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 truncate">{{ $file->original_name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($file->type) }} · {{ round($file->file_size / 1024) }}KB</div>
                                    </div>
                                    @if($file->type === 'input' || ($file->type === 'output' && $order->status === 'completed'))
                                        <a href="{{ route('account.orders.files.download', [$order, $file]) }}" class="btn btn-sm btn-secondary shrink-0">
                                            <x-icon name="download" class="w-3.5 h-3.5" /> Download
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Messages --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Messages ({{ $order->messages->count() }})</h3>

                    @if($order->messages->count())
                        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-1">
                            @foreach($order->messages as $msg)
                                @php $mine = $msg->user_id === auth()->id(); @endphp
                                <div class="flex gap-3 {{ $mine ? 'flex-row-reverse' : '' }}">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $mine ? 'bg-gray-900 text-white' : 'bg-accent-100 text-accent-800' }}">
                                        {{ strtoupper(substr($msg->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="max-w-xs lg:max-w-md">
                                        <div class="px-4 py-2.5 rounded-2xl text-sm {{ $mine ? 'bg-gray-900 text-white rounded-tr-sm' : 'bg-surface-100 text-gray-900 rounded-tl-sm' }}">
                                            {{ $msg->message }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1 {{ $mine ? 'text-right' : '' }}">
                                            {{ $msg->user->name ?? 'Admin' }} · {{ $msg->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 mb-6">No messages yet — ask anything about this order.</p>
                    @endif

                    <form method="POST" action="{{ route('account.orders.message', $order) }}" class="flex gap-3">
                        @csrf
                        <input type="text" name="message" placeholder="Type a message..." required class="form-control-modern flex-1">
                        <button type="submit" class="btn btn-primary shrink-0">Send</button>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Order Summary --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Order Summary</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd class="font-semibold text-gray-900">${{ number_format($order->subtotal, 2) }}</dd></div>
                        @if($order->discount > 0)
                            <div class="flex justify-between"><dt class="text-gray-500">Discount</dt><dd class="font-semibold text-emerald-600">-${{ number_format($order->discount, 2) }}</dd></div>
                        @endif
                        @if($order->tax > 0)
                            <div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd class="font-semibold text-gray-900">${{ number_format($order->tax, 2) }}</dd></div>
                        @endif
                        <div class="flex justify-between pt-3 border-t border-surface-200 text-base">
                            <dt class="font-extrabold text-gray-900">Total</dt>
                            <dd class="font-extrabold text-gray-900">${{ number_format($order->total, 2) }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Order Info --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Priority</dt><dd class="font-medium text-gray-900">{{ ucfirst($order->priority ?? 'Normal') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Created</dt><dd class="font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</dd></div>
                        @if($order->completed_at)
                            <div class="flex justify-between"><dt class="text-gray-500">Completed</dt><dd class="font-medium text-gray-900">{{ $order->completed_at->format('M d, Y') }}</dd></div>
                        @endif
                    </dl>
                </div>

                {{-- Revision Request --}}
                @if(in_array($order->status, ['in_progress', 'revision']))
                    <div class="surface-card p-6 border-accent-300">
                        <h3 class="font-bold text-gray-900 mb-2">Request Revision</h3>
                        <p class="text-xs text-gray-500 mb-4">Revisions are always free — describe what to change.</p>
                        <form method="POST" action="{{ route('account.orders.revision', $order) }}">
                            @csrf
                            <textarea name="message" rows="3" placeholder="Describe the changes you need..." required class="form-control-modern mb-3"></textarea>
                            <button type="submit" class="btn btn-gradient w-full">Submit Revision</button>
                        </form>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="surface-card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                    <a href="{{ route('contact') }}" class="btn btn-secondary w-full">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
