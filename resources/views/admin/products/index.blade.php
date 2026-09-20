@use('App\Support\PreviewGallery')
@extends('admin.layouts.app')
@section('page-title', 'Products')

@section('content')
@php
    // Every image on this page in row order, plus where each product's image sits in it.
    [$previewImages, $previewIndex] = PreviewGallery::for($products, fn ($product) => ['image' => $product->featured_image]);
@endphp

<div x-data="portfolioGallery(@js($previewImages))">
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $products->total() }} total products</p>
    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">+ Add Product</a>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="status" class="admin-filter-select">
        <option value="">All Status</option>
        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
    </select>
    <button type="submit" class="admin-filter-btn">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="admin-table min-w-[640px]">
        <thead class="admin-table-head">
            <tr>
                <th class="admin-th">Image</th>
                <th class="admin-th">Title</th>
                <th class="admin-th">Category</th>
                <th class="admin-th">Price</th>
                <th class="admin-th">Status</th>
                <th class="admin-th text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        @if($product->featured_image)
                            <x-image-thumb :src="asset('storage/' . $product->featured_image)"
                                           :index="$previewIndex[$product->id]['image']"
                                           :alt="$product->title" />
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="admin-td font-medium text-gray-900">{{ $product->title }}</td>
                    <td class="admin-td text-gray-500">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-900 font-medium">${{ number_format($product->price, 2) }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $product->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($product->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Delete this product?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><x-empty-state icon="package" title="No products found" description="Add your first digital product to start selling instantly."><a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Add a product</a></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $products->links() }}</div>

    <x-image-lightbox label="Product images" />
</div>
@endsection
