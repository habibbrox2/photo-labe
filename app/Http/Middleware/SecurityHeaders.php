<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
        $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

        // Cross-Origin isolation headers (COOP/COEP) are only valid in secure (HTTPS)
        // contexts; browsers ignore them over plain HTTP and log a console warning.
        if (! app()->environment('local', 'testing')) {
            $response->headers->set('Cross-Origin-Embedder-Policy', 'unsafe-none');
            $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
        }

        // Content Security Policy
        // In non-production environments, allow the Vite dev server (hot module replacement).
        $devOrigins = '';
        if (! app()->environment('production')) {
            $devOrigins = ' http://localhost:5173 http://127.0.0.1:5173'
                .' ws://localhost:5173 ws://127.0.0.1:5173';
        }

        $csp = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com$devOrigins",
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com$devOrigins",
            "font-src 'self' https://fonts.gstatic.com$devOrigins",
            "img-src 'self' data: blob: storage:$devOrigins",
            "connect-src 'self'$devOrigins",
            "frame-ancestors 'none'",
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // HSTS (only in production)
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
