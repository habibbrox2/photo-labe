@extends('admin.layouts.app')
@section('page-title', 'Create Hero Slide')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.hero-slides.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Image *</label>
                <input type="file" name="image" accept="image/*" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
                <p class="text-xs text-gray-400 mt-1">Recommended: wide landscape, e.g. 1920×1080 (JPG, PNG, or WebP, max 4 MB).</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Headline</label>
                <input type="text" name="headline" value="{{ old('headline') }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">Shown as the big hero text while this slide is active. Leave empty to keep the default homepage headline.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Caption label</label>
                    <input type="text" name="caption_label" value="{{ old('caption_label') }}" maxlength="120" placeholder="e.g. Jewelry" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Caption text</label>
                    <input type="text" name="caption_text" value="{{ old('caption_text') }}" maxlength="255" placeholder="e.g. Diamond Collection" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Link URL</label>
                    <input type="text" name="link_url" value="{{ old('link_url') }}" maxlength="255" placeholder="e.g. /portfolio/diamonds" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sort order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" max="9999" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                <label class="text-sm text-gray-700">Active (visible on the homepage)</label>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Create</button>
            <a href="{{ route('admin.hero-slides.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
