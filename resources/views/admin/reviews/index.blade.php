@extends('admin.layouts.app')
@section('page-title', 'Reviews')

@section('content')
<div class="flex items-center justify-between mb-6">
    <p class="text-sm text-gray-500">{{ $reviews->total() }} total reviews</p>
</div>

<form method="GET" class="flex gap-3 mb-6">
    <select name="status" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:border-accent-500 outline-none">
        <option value="">All Status</option>
        @foreach(['pending','approved','rejected','spam'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">Filter</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">User</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Rating</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Comment</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Date</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $review->user->name ?? 'N/A' }}</td>
                    <td class="px-5 py-3 text-amber-500">{{ $review->rating ? str_repeat('★', $review->rating) : '-' }}</td>
                    <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $review->comment ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $review->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $review->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $review->status === 'spam' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $review->created_at->format('M d, Y') }}</td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if($review->status !== 'approved')
                                <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="px-2 py-1 text-xs font-medium text-green-600 hover:bg-green-50 rounded-lg">Approve</button>
                                </form>
                            @endif
                            @if($review->status !== 'rejected')
                                <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 rounded-lg">Reject</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" data-confirm="Delete this review?" data-confirm-label="Delete">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6"><x-empty-state icon="star" title="No reviews found" description="Reviews customers leave on products and services will appear here for moderation."></x-empty-state></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $reviews->links() }}</div>
@endsection
