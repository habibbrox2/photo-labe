<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'PhotoLabe') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f4f6;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <div style="background-color:#f3f4f6;padding:40px 16px;">
        <div style="max-width:600px;margin:0 auto;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
            {{-- Header --}}
            <div style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);padding:32px 32px;text-align:center;">
                <div style="width:48px;height:48px;margin:0 auto 12px;background:rgba(255,255,255,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:#ffffff;">{{ strtoupper(substr(config('app.name', 'PhotoLabe'), 0, 1)) }}</div>
                <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;letter-spacing:-0.02em;">{{ config('app.name', 'PhotoLabe') }}</h1>
                <p style="margin:6px 0 0;color:rgba(255,255,255,0.75);font-size:13px;">Creative Services &amp; Photo Editing</p>
            </div>

            {{-- Content --}}
            <div style="padding:32px;">
                @yield('content')
            </div>

            {{-- Footer --}}
            <div style="padding:20px 32px;background-color:#f9fafb;text-align:center;border-top:1px solid #f3f4f6;">
                <p style="margin:0;color:#9ca3af;font-size:12px;line-height:1.6;">
                    &copy; {{ date('Y') }} {{ config('app.name', 'PhotoLabe') }} &middot;
                    <a href="{{ config('app.url', '#') }}" style="color:#6b7280;text-decoration:underline;">{{ config('app.url', '') }}</a>
                </p>
                <p style="margin:8px 0 0;color:#d1d5db;font-size:11px;">This is an automated message. Please do not reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>