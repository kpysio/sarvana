<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetIntendedUrl
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('intended')) {
            session(['url.intended' => $request->input('intended')]);
        }
        return $next($request);
    }
} 