<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('auth.token')) {
            return redirect('/')->with('errorMessage', 'Debes iniciar sesión para ingresar.');
        }
        return $next($request);
    }
}
