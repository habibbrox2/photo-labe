@extends('admin.layouts.app')
@section('page-title', 'Contact Message')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-primary-600 hover:underline">&larr; Back to Inbox</a>
</div>

<div class="bg-white rounded-xl border border-gray-200 max-w-3xl">
    <div class="px-6 py-5 border-b border-gray-100">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $message->subject }}</h2>
                <p class="text-sm text-gray-500 mt-1">
                    from <span class="font-medium text-gray-700">{{ $message->name }}</span>
                    &lt;<a href="mailto:{{ $message->email }}" class="text-primary-600 hover:underline">{{ $message->email }}</a>&gt;
                    &middot; {{ $message->created_at->format('M d, Y H:i') }}
                </p>
            </div>
            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ match($message->status) { 'new' => 'bg-amber-100 text-amber-700', 'read' => 'bg-green-100 text-green-700', default => 'bg-gray-100 text-gray-600' } }}">{{ ucfirst($message->status) }}</span>
        </div>
    </div>

    <div class="px-6 py-5">
        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">{{ $message->message }}</p>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap gap-2 justify-between items-center">
        <span class="text-xs text-gray-400">
            @if($message->read_at)
                Read {{ $message->read_at->diffForHumans() }} @if($message->reader) by {{ $message->reader->name }} @endif
            @endif
            @if($message->isReplied())
                <span class="text-green-600 font-medium">&middot; Replied {{ $message->replied_at->diffForHumans() }} @if($message->replier) by {{ $message->replier->name }} @endif</span>
            @endif
        </span>
        <div class="flex gap-2">
            @if($message->status !== 'archived')
                <form method="POST" action="{{ route('admin.contact-messages.archive', $message) }}" data-confirm="Archive this message?">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Archive</button>
                </form>
            @endif
        </div>
    </div>
</div>

{{-- SMTP reply composer --}}
<div class="bg-white rounded-xl border border-gray-200 max-w-3xl mt-4" x-data="{ open: @if($message->isReplied()) false @else true @endif }">
    <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 text-left">
        <span class="text-sm font-semibold text-gray-900">
            @if($message->isReplied())
                Replied &mdash; send another reply
            @else
                Write a reply
            @endif
        </span>
        <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <form method="POST" action="{{ route('admin.contact-messages.reply', $message) }}" x-show="open" x-collapse class="px-6 pb-6">
        @csrf
        <p class="text-xs text-gray-400 mb-3">
            To: <span class="font-medium text-gray-600">{{ $message->name }} &lt;{{ $message->email }}&gt;</span>
            &middot; From: <span class="font-medium text-gray-600">{{ auth()->user()->email }}</span> (sent via SMTP, queued)
        </p>
        <textarea name="reply_body" rows="7" required placeholder="Write your reply here..."
                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-y">{{ old('reply_body', 'Hi ' . $message->name . ",\n\nThank you for reaching out to " . config('app.name', 'PhotoLabe') . ".\n\n") }}</textarea>
        @error('reply_body') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        <div class="flex justify-end mt-3">
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700 inline-flex items-center gap-2">
                <x-icon name="send" class="w-4 h-4" />
                Send Reply
            </button>
        </div>
    </form>
</div>
@endsection
