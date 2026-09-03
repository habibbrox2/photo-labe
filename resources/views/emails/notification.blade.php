@extends('emails.layout')

@section('content')
    @if(!empty($title))
        <h2 style="margin:0 0 16px;color:#111827;font-size:20px;font-weight:700;letter-spacing:-0.01em;">{{ $title }}</h2>
    @endif

    @if(!empty($greeting))
        <p style="margin:0 0 16px;color:#374151;font-size:15px;line-height:1.6;">{{ $greeting }}</p>
    @endif

    @foreach($lines ?? [] as $line)
        <p style="margin:0 0 12px;color:#374151;font-size:15px;line-height:1.6;">{{ $line }}</p>
    @endforeach

    @if(!empty($details) && is_array($details))
        <table role="presentation" style="width:100%;border-collapse:collapse;margin:20px 0;font-size:14px;">
            @foreach($details as $key => $value)
                <tr>
                    <td style="padding:10px 12px;background-color:#f9fafb;color:#6b7280;font-weight:600;width:42%;border:1px solid #f3f4f6;">{{ $key }}</td>
                    <td style="padding:10px 12px;color:#111827;border:1px solid #f3f4f6;">{{ $value }}</td>
                </tr>
            @endforeach
        </table>
    @endif

    @if(!empty($actionUrl))
        <div style="margin:24px 0 8px;">
            <a href="{{ $actionUrl }}" style="display:inline-block;background-color:#4f46e5;color:#ffffff;padding:13px 28px;border-radius:10px;text-decoration:none;font-weight:600;font-size:14px;">{{ $actionText ?? 'View Details' }}</a>
        </div>
    @endif

    @if(!empty($outro))
        <p style="margin:20px 0 0;color:#6b7280;font-size:13px;line-height:1.6;">{{ $outro }}</p>
    @endif

    <p style="margin:24px 0 0;color:#9ca3af;font-size:13px;">Thanks,<br>The {{ config('app.name', 'PhotoLabe') }} Team</p>
@endsection