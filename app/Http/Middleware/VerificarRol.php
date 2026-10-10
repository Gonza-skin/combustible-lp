<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerificarRol
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        $usuario = $request->user();
        if (!$usuario) {
            return redirect()->route('login');
        }

        $rol = DB::table('rol')->where('COD_ROL', $usuario->COD_ROL)->value('DES_ROL');
        if (!in_array($rol, $roles, true)) {
            abort(403, 'No tenés permiso para entrar a esta sección.');
        }

        return $next($request);
    }
}