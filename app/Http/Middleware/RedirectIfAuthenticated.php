<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string $redirectTo = '/ubicaciones'): Response
    {
        if (session('auth.token')) {
            return redirect($redirectTo);
        }
        return $next($request);
    }
}
