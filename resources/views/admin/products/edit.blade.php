@extends('admin.layouts.app')
@section('page-title', 'Edit Product')

@section('content')
<div class="max-w-3xl">
    <x-breadcrumbs :items="[['label' => 'Products', 'url' => route('admin.products.index')], ['label' => 'Edit Product']]" />
    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title *</label>
                <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Category *</label>
                    <select name="category_id" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status *</label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                        <option value="draft" {{ old('status', $product->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $product->status) === 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Price ($) *</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sale Price ($)</label>
                    <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Short Description</label>
                <textarea name="short_description" rows="2" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('short_description', $product->short_description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="5" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('description', $product->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Compatibility</label>
                <input type="text" name="compatibility" value="{{ old('compatibility', $product->compatibility) }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Features (one per line)</label>
                <textarea name="features" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ is_array($product->features) ? implode("\n", $product->features) : old('features', $product->features) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Featured Image</label>
                @if($product->featured_image)
                    <div class="mb-2"><img loading="lazy" decoding="async" src="{{ asset('storage/' . $product->featured_image) }}" class="h-20 rounded-lg object-cover"></div>
                @endif
                <input type="file" name="featured_image" accept="image/*" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600">
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                <label class="text-sm text-gray-700">Featured</label>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6 mt-4 space-y-5">
            <h3 class="font-semibold text-gray-900">SEO</h3>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Title</label>
                <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}" maxlength="255" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">SEO Description</label>
                <textarea name="seo_description" rows="2" maxlength="500" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">{{ old('seo_description', $product->seo_description) }}</textarea>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 text-gray-600 text-sm font-medium hover:bg-gray-100 rounded-lg">Cancel</a>
        </div>
    </form>
</div>
@endsection
