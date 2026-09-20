@extends('emails.layout')

@section('content')
    <h2 style="margin:0 0 16px;color:#111827;font-size:20px;font-weight:700;letter-spacing:-0.01em;">You're in! 🎉</h2>

    <p style="margin:0 0 16px;color:#374151;font-size:15px;line-height:1.6;">
        Thanks for subscribing to the {{ config('app.name', 'PhotoLabe') }} newsletter.
    </p>

    <p style="margin:0 0 12px;color:#374151;font-size:15px;line-height:1.6;">
        Here's what to expect — about twice a month:
    </p>

    <table role="presentation" cellpadding="0" cellspacing="0" style="margin:16px 0 24px;">
        <tr>
            <td style="padding:6px 12px;color:#374151;font-size:15px;line-height:1.7;">• &nbsp;Editing tips &amp; workflow breakdowns</td>
        </tr>
        <tr>
            <td style="padding:6px 12px;color:#374151;font-size:15px;line-height:1.7;">• &nbsp;Free presets, LUTs and templates</td>
        </tr>
        <tr>
            <td style="padding:6px 12px;color:#374151;font-size:15px;line-height:1.7;">• &nbsp;Subscriber-only discounts on digital products</td>
        </tr>
        <tr>
            <td style="padding:6px 12px;color:#374151;font-size:15px;line-height:1.7;">• &nbsp;Before/after case studies from real client work</td>
        </tr>
    </table>

    <div style="margin:24px 0 8px;">
        <a href="{{ url('/products') }}" style="display:inline-block;background-color:#f59e0b;color:#1a1613;padding:13px 28px;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px;">Browse Digital Products</a>
    </div>

    <p style="margin:20px 0 0;color:#6b7280;font-size:13px;line-height:1.6;">
        Changed your mind? <a href="{{ $unsubscribeUrl }}" style="color:#6b7280;">Unsubscribe anytime</a> — one click, no questions asked.
    </p>

    <p style="margin:24px 0 0;color:#9ca3af;font-size:13px;">Cheers,<br>The {{ config('app.name', 'PhotoLabe') }} Team</p>
@endsection
