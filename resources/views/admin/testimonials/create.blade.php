@extends('admin.layouts.app')
@section('page-title', 'Create Testimonial')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" value="{{ old('company') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Rating (1-5)</label>
                    <input type="number" name="rating" value="{{ old('rating', 5) }}" min="1" max="5" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Content *</label>
                <textarea name="content" rows="4" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">{{ old('content') }}</textarea>
            </div>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Active</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Featured</span></label>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-indigo-500 outline-none">
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700">Create</button>
            <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
