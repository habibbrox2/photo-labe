@extends('admin.layouts.app')
@section('page-title', 'Media Library')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $media->total() }} files</p>
</div>

{{-- Upload --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @csrf
        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Files</label>
        <div class="flex items-center gap-4">
            <input type="file" name="files[]" multiple accept="image/*,.pdf,.zip,.psd" class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Upload</button>
        </div>
    </form>
</div>

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="type" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
        <option value="">All Types</option>
        <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
        <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>PDF</option>
        <option value="zip" {{ request('type') === 'zip' ? 'selected' : '' }}>Archives</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
</form>

{{-- Grid --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($media as $item)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden group">
            <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                @if(str_starts_with($item->mime_type, 'image/'))
                    <img loading="lazy" decoding="async" src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                @else
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                @endif
            </div>
            <div class="p-3">
                <p class="text-xs text-gray-700 truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ round($item->file_size / 1024) }}KB</p>
                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs text-gray-400">{{ $item->created_at->format('M d') }}</span>
                    <form method="POST" action="{{ route('admin.media.destroy', $item) }}" data-confirm="Delete this file?" data-confirm-label="Delete">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full"><x-empty-state icon="image" title="No media files found" description="Upload your first image to start building your media library."></x-empty-state></div>
    @endforelse
</div>

<div class="mt-4">{{ $media->links() }}</div>
@endsection
