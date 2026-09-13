<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'PhotoLabe') }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f3ef;font-family:'Plus Jakarta Sans','Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
    <div style="background-color:#f5f3ef;padding:40px 16px;">
        <div style="max-width:600px;margin:0 auto;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.06);">
            {{-- Header --}}
            <div style="background-color:#221e1a;padding:32px 32px;text-align:center;">
                <div style="width:48px;height:48px;margin:0 auto 12px;background:#f59e0b;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:#1a1613;">{{ strtoupper(substr(config('app.name', 'PhotoLabe'), 0, 1)) }}</div>
                <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;letter-spacing:-0.02em;">{{ config('app.name', 'PhotoLabe') }}</h1>
                <p style="margin:6px 0 0;color:rgba(255,255,255,0.65);font-size:13px;">Creative Services &amp; Photo Editing</p>
            </div>

            {{-- Content --}}
            <div style="padding:32px;">
                @yield('content')
            </div>

            {{-- Footer --}}
            <div style="padding:20px 32px;background-color:#fafaf9;text-align:center;border-top:1px solid #e7e5e4;">
                <p style="margin:0;color:#78716c;font-size:12px;line-height:1.6;">
                    &copy; {{ date('Y') }} {{ config('app.name', 'PhotoLabe') }} &middot;
                    <a href="{{ config('app.url', '#') }}" style="color:#57534e;text-decoration:underline;">{{ config('app.url', '') }}</a>
                </p>
                <p style="margin:8px 0 0;color:#a8a29e;font-size:11px;">This is an automated message. Please do not reply directly to this email.</p>
            </div>
        </div>
    </div>
</body>
</html>