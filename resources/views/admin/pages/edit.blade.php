@extends('admin.layouts.app')
@section('page-title', 'Edit Page')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                @if($page->publicUrl())
                    <p class="text-xs text-gray-400 mt-1">
                        Powers <a href="{{ $page->publicUrl() }}" target="_blank" rel="noopener" class="text-primary-600 hover:underline">{{ $page->publicUrl() }}</a>.
                        That URL keeps working whatever the title or slug says.
                    </p>
                @endif
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none font-mono">
                    <p class="text-xs text-gray-400 mt-1">Changing this moves the page, so don't touch it unless you mean to.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Eyebrow</label>
                    <input type="text" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}" maxlength="255" placeholder="e.g. Our Studio" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                    <p class="text-xs text-gray-400 mt-1">Small label above the page heading.</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Subtitle</label>
                <textarea name="subtitle" rows="2" maxlength="500" placeholder="e.g. A professional photo editing studio helping businesses look their best." class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('subtitle', $page->subtitle) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Lead sentence shown under the page heading.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Template</label>
                    <select name="template" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="default" {{ old('template', $page->template) === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="full-width" {{ old('template', $page->template) === 'full-width' ? 'selected' : '' }}>Full Width</option>
                        <option value="sidebar" {{ old('template', $page->template) === 'sidebar' ? 'selected' : '' }}>With Sidebar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Content</label>
                <textarea name="content" rows="18" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none font-mono">{{ old('content', $page->content) }}</textarea>
                <p class="text-xs text-gray-400 mt-1">HTML headings and paragraphs are rendered as written. Shortcodes work here too, e.g. <code class="px-1 rounded bg-gray-100">[before_after id=3]</code>.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>
                @if($page->featured_image)
                    <div class="mb-2"><img loading="lazy" decoding="async" src="{{ asset('storage/' . $page->featured_image) }}" class="h-20 rounded-lg object-cover"></div>
                @endif
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 mt-4 space-y-5">
            <h3 class="font-semibold text-gray-900">SEO</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $page->seo_title) }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Description</label>
                <textarea name="seo_description" rows="2" maxlength="500" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('seo_description', $page->seo_description) }}</textarea>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Update Page</button>
            <a href="{{ route('admin.pages.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
