@extends('admin.layouts.app')
@section('page-title', 'Quote Detail')

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.quotes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">← Back to Quotes</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Quote Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Quote Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $quote->name }}</span></div>
                    <div><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $quote->email }}</span></div>
                    <div><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $quote->phone ?? '-' }}</span></div>
                    <div><span class="text-gray-500">Service:</span> <span class="font-medium">{{ $quote->service->title ?? '-' }}</span></div>
                    <div><span class="text-gray-500">Quantity:</span> <span class="font-medium">{{ $quote->quantity }}</span></div>
                    <div><span class="text-gray-500">Deadline:</span> <span class="font-medium">{{ $quote->deadline?->format('M d, Y') ?? '-' }}</span></div>
                </div>
                @if($quote->requirements)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <span class="text-gray-500 text-sm">Requirements:</span>
                        <p class="mt-1 text-sm text-gray-900">{{ $quote->requirements }}</p>
                    </div>
                @endif
            </div>

            {{-- Files --}}
            @if($quote->files->count())
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Uploaded Files</h3>
                    <div class="space-y-2">
                        @foreach($quote->files as $file)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <span class="text-sm text-gray-700">{{ $file->original_name }}</span>
                                <span class="text-xs text-gray-400">{{ round($file->file_size / 1024) }}KB</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Update Form --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Update Quote</h3>
                <form method="POST" action="{{ route('admin.quotes.update', $quote) }}">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                                @foreach(['pending','reviewing','quoted','accepted','rejected','expired','converted','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $quote->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Quoted Price ($)</label>
                            <input type="number" name="quoted_price" value="{{ $quote->quoted_price }}" step="0.01" min="0"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Admin Notes</label>
                            <textarea name="admin_notes" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ $quote->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">Update Quote</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-3">Details</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">ID</span><span class="font-mono text-gray-700">#{{ $quote->id }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Created</span><span class="text-gray-700">{{ $quote->created_at->format('M d, Y H:i') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Priority</span><span class="text-gray-700">{{ ucfirst($quote->priority) }}</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
