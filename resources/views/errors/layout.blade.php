<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<meta name="theme-color" content="#f59e0b">
<title>@yield('title') — {{ config('app.name', 'PhotoLabe') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --ink: #1c1917;
        --muted: #78716c;
        --surface-50: #fbfaf8;
        --surface-100: #f5f3ef;
        --surface-200: #e8e4dc;
        --accent-100: #fef3c7;
        --accent-300: #fcd34d;
        --accent-500: #f59e0b;
        --accent-600: #d97706;
        --accent-700: #b45309;
        --primary-950: #1a1613;
    }
    body {
        margin: 0;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        color: var(--ink);
        background:
            radial-gradient(circle at 8% 12%, rgba(245, 158, 11, 0.07), transparent 30rem),
            radial-gradient(circle at 92% 85%, rgba(232, 228, 220, 0.55), transparent 28rem),
            var(--surface-50);
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }
    .card {
        background: #fff;
        border: 1px solid var(--surface-200);
        border-radius: 24px;
        box-shadow: 0 24px 60px -36px rgba(28, 25, 23, 0.35);
        padding: 56px 48px;
        text-align: center;
        max-width: 500px;
        margin: 24px;
    }
    .badge {
        width: 72px;
        height: 72px;
        margin: 0 auto 24px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-100);
        color: var(--accent-700);
    }
    .badge svg { width: 34px; height: 34px; }
    .code {
        font-size: 64px;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--ink);
        line-height: 1;
        margin: 0;
    }
    h1 { font-size: 22px; margin: 12px 0 0; font-weight: 800; }
    p { color: var(--muted); line-height: 1.65; margin: 12px 0 28px; font-size: 15px; }
    .actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    a.btn {
        display: inline-block;
        padding: 12px 26px;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: background-color .2s ease, border-color .2s ease;
    }
    a.btn-primary { background: var(--accent-500); color: var(--primary-950); }
    a.btn-primary:hover { background: var(--accent-600); color: #fff; }
    a.btn-ghost { background: #fff; color: var(--ink); border: 1px solid var(--surface-200); }
    a.btn-ghost:hover { background: var(--surface-100); }
    .hint { margin: 28px 0 0; font-size: 12px; color: var(--muted); }
    .hint a { color: var(--accent-700); font-weight: 600; text-decoration: none; }
    .hint a:hover { text-decoration: underline; }
</style>
</head>
<body>
    <div class="card">
        <div class="badge">@yield('icon')</div>
        <p class="code">@yield('code')</p>
        <h1>@yield('heading')</h1>
        <p>@yield('message')</p>
        <div class="actions">
            <a href="@yield('primary_url')" class="btn btn-primary">@yield('primary_label', 'Back to Home')</a>
            <a href="mailto:hello@photolabe.com" class="btn btn-ghost">Contact Support</a>
        </div>
        <p class="hint">Looking for something? <a href="{{ url('/') }}">Browse our services</a> instead.</p>
    </div>
</body>
</html>
