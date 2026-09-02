@extends('layouts.app')
@section('title', 'Order ' . $order->order_number)

@section('content')
<section class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back link --}}
        <a href="{{ route('account.orders') }}" class="text-sm text-indigo-600 hover:text-indigo-700 mb-6 inline-block">← Back to Orders</a>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid lg:grid-cols-3 gap-6">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order Header --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
                            <p class="text-gray-500 text-sm">Placed {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-full text-sm font-medium
                            @match($order->status) {
                                'pending' => 'bg-amber-50 text-amber-700',
                                'in_progress' => 'bg-blue-50 text-blue-700',
                                'revision' => 'bg-orange-50 text-orange-700',
                                'completed' => 'bg-emerald-50 text-emerald-700',
                                'cancelled' => 'bg-red-50 text-red-700',
                                default => 'bg-gray-50 text-gray-700',
                            }">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100">
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Service</div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->service->title ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Quantity</div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->quantity }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Deadline</div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->deadline?->format('M d, Y') ?? 'Flexible' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 mb-1">Total</div>
                            <div class="text-sm font-bold text-indigo-600">${{ number_format($order->total, 2) }}</div>
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-500 mb-1">Your Notes</div>
                            <p class="text-sm text-gray-700">{{ $order->notes }}</p>
                        </div>
                    @endif

                    @if($order->admin_notes)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-500 mb-1">Admin Notes</div>
                            <p class="text-sm text-gray-700">{{ $order->admin_notes }}</p>
                        </div>
                    @endif
                </div>

                {{-- Order Progress Timeline --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Order Progress</h3>
                    <div class="space-y-4">
                        @php
                            $steps = [
                                'pending' => ['label' => 'Order Placed', 'icon' => '📋'],
                                'in_progress' => ['label' => 'In Progress', 'icon' => '⚙️'],
                                'revision' => ['label' => 'Revision', 'icon' => '🔄'],
                                'completed' => ['label' => 'Completed', 'icon' => '✅'],
                            ];
                            $statusOrder = ['pending', 'in_progress', 'revision', 'completed'];
                            $currentIndex = array_search($order->status, $statusOrder);
                            if ($currentIndex === false) $currentIndex = -1;
                        @endphp

                        @foreach($steps as $key => $step)
                            @php
                                $stepIndex = array_search($key, $statusOrder);
                                $isCompleted = $stepIndex !== false && $stepIndex <= $currentIndex;
                                $isCurrent = $key === $order->status;
                            @endphp
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                    {{ $isCompleted ? 'bg-indigo-100' : 'bg-gray-100' }}">
                                    {{ $step['icon'] }}
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium {{ $isCompleted ? 'text-gray-900' : 'text-gray-400' }}">{{ $step['label'] }}</div>
                                </div>
                                @if($isCurrent)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Current</span>
                                @elseif($isCompleted && $key !== 'pending')
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                @endif
                            </div>
                        @endforeach

                        @if($order->status === 'cancelled')
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-red-100 text-lg">❌</div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-red-600">Cancelled</div>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Current</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Files --}}
                @if($order->files->count())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Files</h3>
                        <div class="space-y-3">
                            @foreach($order->files as $file)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                        @if(str_starts_with($file->mime_type, 'image/'))
                                            <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @else
                                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $file->original_name }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($file->type) }} · {{ round($file->file_size / 1024) }}KB</div>
                                    </div>
                                    @if($file->type === 'output' && $order->status === 'completed')
                                        <a href="{{ Storage::disk('public')->url($file->file_path) }}" download class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Download</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Messages --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Messages ({{ $order->messages->count() }})</h3>

                    @if($order->messages->count())
                        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                            @foreach($order->messages as $msg)
                                <div class="flex gap-3 {{ $msg->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                    <div class="w-8 h-8 rounded-full bg-{{ $msg->user_id === auth()->id() ? 'indigo' : 'gray' }}-100 flex items-center justify-center text-xs font-bold text-{{ $msg->user_id === auth()->id() ? 'indigo' : 'gray' }}-600 flex-shrink-0">
                                        {{ strtoupper(substr($msg->user->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div class="max-w-xs lg:max-w-md">
                                        <div class="px-4 py-2.5 rounded-2xl text-sm
                                            {{ $msg->user_id === auth()->id() ? 'bg-indigo-600 text-white rounded-tr-sm' : 'bg-gray-100 text-gray-900 rounded-tl-sm' }}">
                                            {{ $msg->message }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1 {{ $msg->user_id === auth()->id() ? 'text-right' : '' }}">
                                            {{ $msg->user->name ?? 'Admin' }} · {{ $msg->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 mb-6">No messages yet.</p>
                    @endif

                    {{-- Send Message --}}
                    <form method="POST" action="{{ route('account.orders.message', $order) }}" class="flex gap-3">
                        @csrf
                        <input type="text" name="message" placeholder="Type a message..." required
                            class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 text-sm">
                            Send
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Order Summary --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Order Summary</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="text-gray-900">${{ number_format($order->subtotal, 2) }}</span></div>
                        @if($order->discount > 0)
                            <div class="flex justify-between"><span class="text-gray-500">Discount</span><span class="text-emerald-600">-${{ number_format($order->discount, 2) }}</span></div>
                        @endif
                        @if($order->tax > 0)
                            <div class="flex justify-between"><span class="text-gray-500">Tax</span><span class="text-gray-900">${{ number_format($order->tax, 2) }}</span></div>
                        @endif
                        <div class="flex justify-between pt-3 border-t border-gray-100 font-bold">
                            <span class="text-gray-900">Total</span>
                            <span class="text-indigo-600">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Order Info --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Details</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Priority</span><span class="text-gray-900">{{ ucfirst($order->priority ?? 'Normal') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Created</span><span class="text-gray-900">{{ $order->created_at->format('M d, Y') }}</span></div>
                        @if($order->completed_at)
                            <div class="flex justify-between"><span class="text-gray-500">Completed</span><span class="text-gray-900">{{ $order->completed_at->format('M d, Y') }}</span></div>
                        @endif
                    </div>
                </div>

                {{-- Revision Request --}}
                @if(in_array($order->status, ['in_progress', 'revision']))
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Request Revision</h3>
                        <form method="POST" action="{{ route('account.orders.revision', $order) }}">
                            @csrf
                            <textarea name="message" rows="3" placeholder="Describe the changes you need..." required
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-indigo-500 outline-none mb-3"></textarea>
                            <button type="submit" class="w-full px-4 py-2.5 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 text-sm">
                                Submit Revision
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('contact') }}" class="block w-full text-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
