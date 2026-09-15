@extends('admin.layouts.app')
@section('page-title', 'Create Page')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" maxlength="255" placeholder="auto from the title" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none font-mono">
                    <p class="text-xs text-gray-400 mt-1">Public path: /page/&lt;slug&gt;</p>
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Eyebrow</label>
                    <input type="text" name="eyebrow" value="{{ old('eyebrow') }}" maxlength="255" placeholder="e.g. Our Studio" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Small label above the page heading.</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Subtitle</label>
                <textarea name="subtitle" rows="2" maxlength="500" placeholder="e.g. A professional photo editing studio helping businesses look their best." class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('subtitle') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Lead sentence shown under the page heading.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Template</label>
                    <select name="template" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="default">Default</option>
                        <option value="full-width">Full Width</option>
                        <option value="sidebar">With Sidebar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Content</label>
                <textarea name="content" rows="18" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none font-mono">{{ old('content') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">HTML headings and paragraphs are rendered as written. Shortcodes work here too, e.g. <code class="px-1 rounded bg-gray-100">[before_after id=3]</code>.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 mt-4 space-y-5">
            <h3 class="font-semibold text-gray-900">SEO</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title') }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Description</label>
                <textarea name="seo_description" rows="2" maxlength="500" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('seo_description') }}</textarea>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Create Page</button>
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
