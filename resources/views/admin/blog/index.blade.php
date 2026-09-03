@extends('admin.layouts.app')
@section('page-title', 'Blog Posts')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $posts->total() }} total posts</p>
    <a href="{{ route('admin.blog.create') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">+ Add Post</a>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <select name="status" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
        <option value="">All Status</option>
        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Author</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">
                        @if($post->status === 'published' && $post->published_at?->isPast())
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="hover:text-primary-600 transition-colors">{{ $post->title }}</a>
                        @else
                            {{ $post->title }}
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $post->category->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $post->author->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $post->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($post->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $post->published_at?->format('M d, Y') ?? '-' }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($post->status === 'published' && $post->published_at?->isPast())
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 rounded-lg" title="View on site">View</a>
                            @endif
                            <a href="{{ route('admin.blog.edit', $post) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">Edit</a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" data-confirm="Delete this post?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><x-empty-state icon="document" title="No posts found" description="Start writing — your first blog post will appear here."><a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">Write a post</a></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $posts->links() }}</div>
@endsection
