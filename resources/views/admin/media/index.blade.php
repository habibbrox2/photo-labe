@extends('admin.layouts.app')

@push('styles')
<style>
  .media-lightbox-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.92);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5rem 5rem 7rem;
  }
    [x-cloak] {
        display: none !important;
    }
    .media-lightbox {
        position: fixed;
        inset: 0;
        z-index: 9998;
    }
  .media-lightbox-img {
    max-width: min(100%, 1100px);
    max-height: calc(100vh - 11rem);
    object-fit: contain;
    border-radius: 8px;
  }
  .media-lightbox-close {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    color: white;
    background: rgba(255,255,255,0.1);
    border: none;
    border-radius: 50%;
    width: 48px;
    height: 48px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }
  .media-lightbox-close:hover {
    background: rgba(255,255,255,0.2);
  }
  .media-lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    background: rgba(255,255,255,0.1);
    border: none;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }
  .media-lightbox-nav:hover {
    background: rgba(255,255,255,0.2);
  }
  .media-lightbox-nav-left {
    left: 1.5rem;
  }
  .media-lightbox-nav-right {
    right: 1.5rem;
  }
  .media-lightbox-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 2.5rem 2.5rem 1.5rem;
    pointer-events: none;
  }
  .media-lightbox-info h3 {
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
  }
  .media-lightbox-info p {
    font-size: 0.875rem;
    opacity: 0.8;
  }
    .media-upload-row,
    .media-filter-row {
        min-width: 0;
    }
    .media-upload-input {
        min-width: 0;
    }
    @media (max-width: 639px) {
        .media-lightbox-overlay {
            padding: 4.5rem 1rem 6.5rem;
        }
        .media-lightbox-close {
            top: 1rem;
            right: 1rem;
        }
        .media-lightbox-nav {
            top: auto;
            bottom: 1.5rem;
            transform: none;
            width: 44px;
            height: 44px;
        }
        .media-lightbox-nav-left { left: 1rem; }
        .media-lightbox-nav-right { right: 1rem; }
        .media-lightbox-info {
            padding: 1.25rem 1rem 5rem;
        }
        .media-lightbox-info h3 {
            max-width: calc(100vw - 2rem);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
    }
    }
</style>
@endpush

@section('page-title', 'Media Library')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $media->total() }} total files</p>
</div>

{{-- Upload --}}
<div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-semibold text-gray-700">Upload Files</label>
            <p class="mt-1 text-xs text-gray-400">Add images and project files to your media library.</p>
        </div>
        <div class="media-upload-row flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
            <input type="file" name="files[]" multiple accept="image/*,.pdf,.zip,.psd" class="media-upload-input w-full flex-1 px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
            <button type="submit" class="w-full sm:w-auto shrink-0 px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Upload</button>
        </div>
    </form>
</div>

{{-- Filters --}}
<form method="GET" class="media-filter-row grid grid-cols-1 sm:grid-cols-[minmax(0,1fr)_auto_auto] gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search files..." class="w-full min-w-0 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="type" class="w-full sm:w-auto px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
        <option value="">All Types</option>
        <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
        <option value="pdf" {{ request('type') === 'pdf' ? 'selected' : '' }}>PDF</option>
        <option value="zip" {{ request('type') === 'zip' ? 'selected' : '' }}>Archives</option>
    </select>
    <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
</form>

{{-- Grid --}}
<div class="grid grid-cols-1 min-[420px]:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"
     x-data="mediaGrid()"
     x-init="init()">
    <script type="application/json" data-media-grid>@json($mediaItems)</script>
    @forelse($media as $item)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden group hover:shadow-md transition-shadow">
            <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden cursor-pointer"
                 @click="openLightbox({{ $loop->index }})">
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

{{-- Full-Screen Lightbox Modal --}}
<div class="media-lightbox" x-data="mediaLightbox" x-cloak x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @keydown.escape.window="closeLightbox()" @keydown.arrow-left.window="prev()" @keydown.arrow-right.window="next()">
    <div class="media-lightbox-overlay" @click="closeLightbox()"></div>
    
    <button class="media-lightbox-close" @click="closeLightbox()" aria-label="Close">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    
    <button class="media-lightbox-nav media-lightbox-nav-left" @click="prev()" aria-label="Previous" x-show="items.length > 1">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
    </button>
    
    <button class="media-lightbox-nav media-lightbox-nav-right" @click="next()" aria-label="Next" x-show="items.length > 1">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
    </button>
    
    <template x-if="items.length > 0">
        <img class="media-lightbox-img" :src="items[index].url" :alt="items[index].name" x-show="items[index].isImage">
        <div x-show="!items[index].isImage" class="text-white text-center">
            <svg class="w-24 h-24 mx-auto mb-4 opacity-60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <p class="text-lg font-medium" x-text="items[index].name"></p>
            <p class="text-sm opacity-75 mt-2" x-text="items[index].mime"></p>
        </div>
    </template>
    
    <div class="media-lightbox-info" x-show="items.length > 0">
        <h3 x-text="items[index]?.name || ''"></h3>
        <p>
            <span x-text="items[index]?.size || ''"></span>
            <span x-show="items[index]?.dimensions"> · </span>
            <span x-text="items[index]?.dimensions || ''"></span>
            <span x-show="items[index]?.date"> · </span>
            <span x-text="items[index]?.date || ''"></span>
        </p>
    </div>
</div>
@endsection
