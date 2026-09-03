@extends('admin.layouts.app')
@section('page-title', 'Edit Before/After')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.before-after.update', $project) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title', $project->title) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="">None</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $project->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="draft" {{ old('status', $project->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $project->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Before Image</label>
                    @if($project->before_image)
                        <div class="mb-2"><img loading="lazy" decoding="async" src="{{ asset('storage/' . $project->before_image) }}" class="h-20 rounded-lg object-cover"></div>
                    @endif
                    <input type="file" name="before_image" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">After Image</label>
                    @if($project->after_image)
                        <div class="mb-2"><img loading="lazy" decoding="async" src="{{ asset('storage/' . $project->after_image) }}" class="h-20 rounded-lg object-cover"></div>
                    @endif
                    <input type="file" name="after_image" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $project->sort_order) }}" min="0" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                    <label class="text-sm text-gray-700">Featured</label>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Update</button>
            <a href="{{ route('admin.before-after.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
