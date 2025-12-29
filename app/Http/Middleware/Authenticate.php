<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (!session('auth.token')) {
            // No autenticado: redirigir al login con mensaje
            return redirect('/')->with('errorMessage', 'Debes iniciar sesión para ingresar.');
        }
        return $next($request);
    }
}
