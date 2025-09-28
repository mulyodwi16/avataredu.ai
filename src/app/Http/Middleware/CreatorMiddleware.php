<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CreatorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isCreator()) {
            return redirect()->route('dashboard')
                ->with('error', 'You need to be a creator to access this area.');
        }

        return $next($request);
    }
}