@use('App\Support\PreviewGallery')
@extends('admin.layouts.app')
@section('page-title', 'Portfolio')

@section('content')
@php
    // Every image on this page in row order, plus where each project's image sits in it.
    [$previewImages, $previewIndex] = PreviewGallery::for($projects, fn ($project) => ['image' => $project->featured_image]);
@endphp

<div x-data="portfolioGallery(@js($previewImages))">
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $projects->total() }} total projects</p>
    <a href="{{ route('admin.portfolio.create') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">+ Add Project</a>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..." class="flex-1 px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
    <button type="submit" class="admin-filter-btn">Search</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="admin-table min-w-[640px]">
        <thead class="admin-table-head">
            <tr>
                <th class="admin-th">Image</th>
                <th class="admin-th">Title</th>
                <th class="admin-th">Category</th>
                <th class="admin-th">Client</th>
                <th class="admin-th">Status</th>
                <th class="admin-th text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($projects as $project)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3">
                        @if($project->featured_image)
                            <x-image-thumb :src="asset('storage/' . $project->featured_image)"
                                           :index="$previewIndex[$project->id]['image']"
                                           :alt="$project->title" />
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="admin-td font-medium text-gray-900">{{ $project->title }}</td>
                    <td class="admin-td text-gray-500">{{ $project->category->name ?? '-' }}</td>
                    <td class="admin-td text-gray-500">{{ $project->client ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $project->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($project->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.portfolio.edit', $project) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">Edit</a>
                            <form method="POST" action="{{ route('admin.portfolio.destroy', $project) }}" data-confirm="Delete this project?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><x-empty-state icon="image" title="No projects found" description="Showcase your best work — add your first portfolio project."><a href="{{ route('admin.portfolio.create') }}" class="btn btn-primary btn-sm">Add a project</a></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $projects->links() }}</div>

    <x-image-lightbox label="Portfolio images" />
</div>
@endsection
