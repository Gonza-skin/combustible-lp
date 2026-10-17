<?php

namespace App\Http\Middleware;

use App\Models\Usuario;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AutomovilAccess
{
    public static function canAccess(?Authenticatable $user): bool
    {
        return self::isAdministrator($user) || self::isStationManager($user);
    }

    public static function isAdministrator(?Authenticatable $user): bool
    {
        return in_array(self::roleName($user), [
            'administrador',
            'administrador_sistema',
            'administrador_de_sistema',
            'admin',
            'admin_sistema',
        ], true);
    }

    public static function isStationManager(?Authenticatable $user): bool
    {
        return in_array(self::roleName($user), [
            'encargado_estacion',
            'encargado_de_estacion',
        ], true);
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless(self::canAccess($user), 403);

        if (! self::isAdministrator($user)) {
            abort_unless(
                $request->routeIs('automoviles.index', 'automoviles.show'),
                403
            );
        }

        return $next($request);
    }

    private static function roleName(?Authenticatable $user): string
    {
        if (! $user instanceof Usuario) {
            return '';
        }

        return Str::of((string) $user->rol?->DES_ROL)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->toString();
    }
}
