<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->getAttribute('role') === null) {
            return $next($request);
        }

        return redirect()->route('dashboard');
    }
}
