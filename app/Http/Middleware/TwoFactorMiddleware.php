<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Permite continuar si existe la sesión temporal para 2FA
        if (session()->has('2fa:user:id')) {
            return $next($request);
        }

        // Si no, redirige a login porque no está autorizado a ver esta página
        return redirect()->route('login')->withErrors(['message' => 'Por favor inicia sesión primero.']);
    }
}
