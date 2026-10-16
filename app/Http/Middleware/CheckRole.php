<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Deja pasar solo a los usuarios cuyo rol (DES_ROL) esté en la lista.
 *
 * Uso en rutas (siempre junto con 'auth'):
 *   Route::middleware(['auth', 'role:administrador'])->group(...);
 *   Route::middleware(['auth', 'role:administrador,supervisor'])->group(...);
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        // Sin sesión → al login (por si alguien olvida poner 'auth' antes)
        if (! $usuario) {
            return redirect()->route('login');
        }

        // Con sesión pero sin el rol necesario → 403
        abort_unless($usuario->tieneRol(...$roles), 403, 'No tienes permiso para entrar aquí.');

        return $next($request);
    }
}