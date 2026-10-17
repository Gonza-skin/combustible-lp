<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CiudadanoAccess
{
    public static function canAccess(?Authenticatable $user): bool
    {
        if (! $user instanceof Usuario) {
            return false;
        }

        return Str::of((string) $user->rol?->DES_ROL)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->toString() === 'ciudadano';
    }

    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(self::canAccess($request->user()), 403);

        return $next($request);
    }
}
