@extends('admin.layouts.app')
@section('page-title', 'Before / After')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $projects->total() }} total items</p>
    <a href="{{ route('admin.before-after.create') }}" class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">+ Add Item</a>
</div>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Title</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($projects as $project)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $project->title }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $project->category->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $project->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ ucfirst($project->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.before-after.edit', $project) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">Edit</a>
                            <form method="POST" action="{{ route('admin.before-after.destroy', $project) }}" data-confirm="Delete this item?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4"><x-empty-state icon="camera" title="No before/after projects found" description="Add transformations to showcase your retouching skills."><a href="{{ route('admin.before-after.create') }}" class="btn btn-primary btn-sm">Add a project</a></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $projects->links() }}</div>
@endsection
