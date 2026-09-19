@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 16px;color:#111827;font-size:20px;font-weight:700;letter-spacing:-0.01em;">
        Re: {{ $contactMessage->subject }}
    </h2>

    <p style="margin:0 0 16px;color:#374151;font-size:15px;line-height:1.6;">
        Hello {{ $contactMessage->name }},
    </p>

    <p style="margin:0 0 12px;color:#374151;font-size:15px;line-height:1.6;">
        {{ $staff->name }} from {{ config('app.name', 'PhotoLabe') }} has replied to your message:
    </p>

    <div style="margin:20px 0;padding:20px;background-color:#f9fafb;border-left:4px solid #f59e0b;border-radius:8px;">
        <p style="margin:0;color:#111827;font-size:15px;line-height:1.7;white-space:pre-line;">{{ $replyBody }}</p>
    </div>

    <p style="margin:20px 0 0;color:#6b7280;font-size:13px;line-height:1.6;">
        Reply directly to this email to continue the conversation — it will reach {{ $staff->name }} ({{ $staff->email }}).
    </p>

    <div style="margin:20px 0 0;padding-top:16px;border-top:1px solid #f3f4f6;">
        <p style="margin:0;color:#9ca3af;font-size:12px;">
            Your original message ({{ $contactMessage->created_at->format('M d, Y') }}):<br>
            <span style="color:#6b7280;">{{ \Illuminate\Support\Str::limit($contactMessage->message, 300) }}</span>
        </p>
    </div>
@endsection
