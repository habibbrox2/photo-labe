<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Too Many Requests — {{ config('app.name', 'PhotoLabe') }}</title>
<style>
    body{margin:0;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:#f3f4f6;color:#111827;display:flex;align-items:center;justify-content:center;min-height:100vh}
    .card{background:#fff;border-radius:20px;box-shadow:0 10px 40px rgba(0,0,0,.06);padding:48px;text-align:center;max-width:480px;margin:24px}
    .code{font-size:72px;font-weight:800;letter-spacing:-.03em;background:linear-gradient(135deg,#f59e0b,#d97706);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;margin:0}
    h1{font-size:22px;margin:8px 0}
    p{color:#6b7280;line-height:1.6;margin:0 0 24px}
    a{display:inline-block;background:#4f46e5;color:#fff;padding:12px 28px;border-radius:999px;text-decoration:none;font-weight:600}
</style>
</head>
<body>
    <div class="card">
        <p class="code">429</p>
        <h1>Too Many Requests</h1>
        <p>You've made too many requests in a short time. Please wait a moment and try again.</p>
        <a href="{{ url('/') }}">Back to Home</a>
    </div>
</body>
</html>