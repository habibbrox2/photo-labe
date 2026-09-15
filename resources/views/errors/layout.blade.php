<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<meta name="theme-color" content="#102a43">
<title>@yield('title') — {{ config('app.name', 'PhotoLabe') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --navy: #102a43;
        --navy-deep: #071a2b;
        --blue: #2f80ed;
        --blue-soft: #dcecff;
        --orange: #f2994a;
        --muted: #627d98;
        --paper: #f4f8fc;
        --line: #d9e5f0;
        --white: #fff;
    }
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: 'Manrope', ui-sans-serif, system-ui, sans-serif;
        color: var(--navy);
        background:
            linear-gradient(135deg, rgba(47, 128, 237, .08), transparent 42%),
            radial-gradient(circle at 90% 10%, rgba(242, 153, 74, .18), transparent 25rem),
            var(--paper);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 32px 20px;
        overflow-x: hidden;
    }
    .card {
        position: relative;
        width: min(100%, 720px);
        background: var(--white);
        border: 1px solid var(--line);
        border-radius: 8px;
        box-shadow: 0 28px 70px rgba(16, 42, 67, .14);
        padding: 44px clamp(24px, 7vw, 72px) 34px;
        text-align: center;
        overflow: hidden;
        animation: rise .55s ease-out both;
    }
    .card::before {
        content: '';
        position: absolute;
        inset: 0 0 auto;
        height: 6px;
        background: linear-gradient(90deg, var(--blue), var(--orange));
    }
    .brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 40px;
        color: var(--navy);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .brand-mark {
        width: 28px;
        height: 28px;
        border-radius: 7px;
        background: var(--navy);
        box-shadow: 8px 8px 0 var(--orange);
    }
    .badge {
        width: 64px;
        height: 64px;
        margin: 0 auto 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--blue-soft);
        color: var(--blue);
    }
    .badge svg { width: 34px; height: 34px; }
    .code {
        font-family: 'DM Mono', monospace;
        font-size: clamp(58px, 10vw, 88px);
        font-weight: 800;
        letter-spacing: -.08em;
        color: var(--navy);
        line-height: 1;
        margin: 0;
    }
    h1 { font-size: clamp(22px, 4vw, 30px); margin: 18px 0 0; font-weight: 800; letter-spacing: -.03em; }
    .message { color: var(--muted); line-height: 1.7; margin: 12px auto 30px; font-size: 15px; max-width: 470px; }
    .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-top: 30px; }
    a.btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 46px;
        padding: 12px 24px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: transform .2s ease, background-color .2s ease, border-color .2s ease;
    }
    a.btn:hover { transform: translateY(-2px); }
    a.btn-primary { background: var(--navy); color: var(--white); }
    a.btn-primary:hover { background: var(--blue); }
    a.btn-ghost { background: var(--white); color: var(--navy); border: 1px solid var(--line); }
    a.btn-ghost:hover { background: var(--blue-soft); border-color: var(--blue); }
    .hint { margin: 30px 0 0; font-size: 12px; color: var(--muted); }
    .hint a { color: var(--blue); font-weight: 700; text-decoration: none; }
    .hint a:hover { text-decoration: underline; }
    .error-details { margin-top: 30px; text-align: left; }
    .error-details-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 10px; }
    .error-details-title { margin: 0; color: var(--navy); font-size: 13px; font-weight: 800; }
    .copy-button { display: inline-flex; align-items: center; gap: 7px; border: 0; border-radius: 4px; padding: 8px 11px; background: var(--navy); color: var(--white); cursor: pointer; font: 600 11px 'Manrope', sans-serif; }
    .copy-button:hover { background: var(--blue); }
    .copy-button svg { width: 15px; height: 15px; }
    .error-markdown { max-height: 330px; overflow: auto; padding: 16px; border-radius: 4px; background: var(--navy-deep); color: #dcecff; font: 12px/1.7 'DM Mono', monospace; white-space: pre-wrap; overflow-wrap: anywhere; }
    .credit { margin: 38px 0 0; color: var(--muted); font-family: 'DM Mono', monospace; font-size: 11px; letter-spacing: .12em; text-transform: uppercase; }
    @keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    @media (max-width: 520px) { .card { padding-top: 34px; } .brand { margin-bottom: 30px; } a.btn { width: 100%; } .error-details-head { align-items: flex-start; flex-direction: column; } }
</style>
</head>
<body>
    <div class="card">
        <div class="brand"><span class="brand-mark" aria-hidden="true"></span>{{ config('app.name', 'PhotoLabe') }}</div>
        <div class="badge">@yield('icon')</div>
        <p class="code">@yield('code')</p>
        <h1>@yield('heading')</h1>
        <p class="message">@yield('message')</p>
        @yield('content')
        <div class="actions">
            <a href="@yield('primary_url')" class="btn btn-primary">@yield('primary_label', 'Back to Home')</a>
            <a href="mailto:hello@photolabe.com" class="btn btn-ghost">Contact Support</a>
        </div>
        <p class="hint">Looking for something? <a href="{{ url('/') }}">Browse our services</a> instead.</p>
        <p class="credit">DEV BY HR HABIB</p>
    </div>
    @stack('scripts')
</body>
</html>
