@extends('admin.layouts.app')
@section('page-title', 'Order Detail')

@section('content')
<div class="max-w-4xl">
    <x-breadcrumbs :items="[['label' => 'Orders', 'url' => route('admin.orders.index')], ['label' => $order->order_number]]" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Order Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Order #:</span> <span class="font-mono font-medium">{{ $order->order_number }}</span></div>
                    <div><span class="text-gray-500">Customer:</span> <span class="font-medium">{{ $order->user->name ?? 'N/A' }}</span></div>
                    <div><span class="text-gray-500">Service:</span> <span class="font-medium">{{ $order->service->title ?? '-' }}</span></div>
                    <div><span class="text-gray-500">Quantity:</span> <span class="font-medium">{{ $order->quantity }}</span></div>
                    <div><span class="text-gray-500">Subtotal:</span> <span class="font-medium">{{ money($order->subtotal, $order->currency) }}</span></div>
                    <div><span class="text-gray-500">Total:</span> <span class="font-bold text-lg">{{ money($order->total, $order->currency) }}</span></div>
                    <div><span class="text-gray-500">Deadline:</span> <span class="font-medium">{{ $order->deadline?->format('M d, Y') ?? '-' }}</span></div>
                    <div><span class="text-gray-500">Created:</span> <span class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</span></div>
                </div>
                @if($order->notes)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-gray-500 text-sm">Notes:</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Files --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Files</h3>

                {{-- Upload Form --}}
                <form method="POST" action="{{ route('admin.orders.files.upload', $order) }}" enctype="multipart/form-data" class="mb-4 p-4 bg-gray-50 rounded-lg">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select name="type" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                            <option value="output">Deliverable (output)</option>
                            <option value="input">Input / reference</option>
                        </select>
                        <input type="file" name="file" required accept=".jpg,.jpeg,.png,.webp,.tiff,.zip,.psd"
                            class="flex-1 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
                        <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Upload</button>
                    </div>
                    @error('file') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-2">Max 50MB. Allowed: JPG, PNG, WebP, TIFF, ZIP, PSD. Files are stored privately and never exposed publicly.</p>
                </form>

                @if($order->files->count())
                    <div class="space-y-2">
                        @foreach($order->files as $file)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span class="text-sm text-gray-700 flex-1 min-w-0 truncate">{{ $file->original_name }}</span>
                                <span class="px-2 py-0.5 text-xs rounded-full {{ $file->type === 'input' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($file->type) }}</span>
                                <a href="{{ route('admin.orders.files.download', [$order, $file]) }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">Download</a>
                                <form method="POST" action="{{ route('admin.orders.files.destroy', [$order, $file]) }}"
                                    data-confirm="Delete this file? This cannot be undone." data-confirm-label="Delete">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 text-center py-4">No files uploaded yet.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Update Status</h3>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                                @foreach(['pending','confirmed','paid','processing','quality_check','revision','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Admin Notes</label>
                            <textarea name="admin_notes" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ $order->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Update Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
