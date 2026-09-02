<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EditorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isEditor()) {
            abort(403, 'Unauthorized. Editor access required.');
        }

        return $next($request);
    }
}
