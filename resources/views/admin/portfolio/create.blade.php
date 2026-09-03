@extends('admin.layouts.app')
@section('page-title', 'Create Portfolio Project')

@section('content')
<div class="max-w-3xl">
    <x-breadcrumbs :items="[['label' => 'Portfolio', 'url' => route('admin.portfolio.index')], ['label' => 'Create Project']]" />
    <form method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="draft">Draft</option>
                        <option value="published" selected>Published</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Client</label>
                    <input type="text" name="client" value="{{ old('client') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">URL</label>
                    <input type="url" name="url" value="{{ old('url') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Gallery Images (multiple)</label>
                <input type="file" name="gallery_images[]" multiple accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
                <p class="text-xs text-gray-400 mt-1">Upload multiple images for the project gallery</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($tags as $tag)
                        <label class="flex items-center gap-1 text-sm">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                <label class="text-sm text-gray-700">Featured</label>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Create Project</button>
            <a href="{{ route('admin.portfolio.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
