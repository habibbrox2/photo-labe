@extends('admin.layouts.app')
@section('page-title', 'Create Service')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                        <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ old('short_description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="6" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Starting Price ($)</label>
                    <input type="number" name="starting_price" value="{{ old('starting_price') }}" step="0.01" min="0"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Delivery Time</label>
                    <input type="text" name="delivery_time" value="{{ old('delivery_time') }}" placeholder="e.g., 24 hours"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600">
                <label class="text-sm text-gray-700">Featured</label>
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mt-4 space-y-5">
            <h3 class="font-semibold text-gray-900">SEO</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title') }}" maxlength="255"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Description</label>
                <textarea name="seo_description" rows="2" maxlength="500" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ old('seo_description') }}</textarea>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition-colors">Create Service</button>
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
