@extends('admin.layouts.app')
@section('page-title', 'Contact Inbox')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <p class="text-sm text-gray-500">
        {{ $messages->total() }} total messages
        @if($newCount)
            <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">{{ $newCount }} new</span>
        @endif
    </p>
    <div class="flex gap-1">
        @foreach(['' => 'All', 'new' => 'New', 'read' => 'Read', 'archived' => 'Archived'] as $value => $label)
            <a href="{{ route('admin.contact-messages.index', array_filter(['status' => $value, 'search' => request('search')])) }}"
               class="px-3 py-1.5 text-xs font-medium rounded-lg {{ request('status', '') === $value ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<form method="GET" action="{{ route('admin.contact-messages.index') }}" class="mb-4 flex gap-2 max-w-md">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email or subject..."
           class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
    <button type="submit" class="px-4 py-2 text-sm font-medium bg-gray-900 text-white rounded-lg hover:bg-gray-800">Search</button>
</form>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
    <table class="w-full text-sm min-w-[640px]">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">From</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Subject</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Received</th>
                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($messages as $msg)
                <tr class="hover:bg-gray-50 {{ $msg->status === 'new' ? 'font-semibold' : '' }}">
                    <td class="px-5 py-3">
                        <span class="text-gray-900">{{ $msg->name }}</span>
                        <span class="block text-xs text-gray-400 font-normal">{{ $msg->email }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-600 max-w-xs truncate">{{ $msg->subject }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ match($msg->status) { 'new' => 'bg-amber-100 text-amber-700', 'read' => 'bg-green-100 text-green-700', default => 'bg-gray-100 text-gray-600' } }}">{{ ucfirst($msg->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">
                        {{ $msg->created_at->diffForHumans() }}
                        @if($msg->isReplied())
                            <span class="block text-[11px] text-green-600 font-medium">Replied</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.contact-messages.show', $msg) }}" class="px-3 py-1 text-xs font-medium text-primary-600 hover:bg-primary-50 rounded-lg">View</a>
                            @if($msg->status !== 'archived')
                                <form method="POST" action="{{ route('admin.contact-messages.archive', $msg) }}" data-confirm="Archive this message?">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 text-xs font-medium text-gray-500 hover:bg-gray-100 rounded-lg">Archive</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5"><x-empty-state icon="chat" title="No messages found" description="Contact form submissions will appear here." /></td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
<div class="mt-4">{{ $messages->links() }}</div>
@endsection
