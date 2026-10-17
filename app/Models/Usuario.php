<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'COD_USU';
    public $timestamps = false;

    protected $fillable = [
        'NOM_USU', 'EML_USU', 'PAS_USU', 'COD_ROL', 'COD_EST', 'ACT_USU',
    ];

    protected $hidden = ['PAS_USU'];

    protected function casts(): array
    {
        return ['ACT_USU' => 'boolean'];
    }

    // Laravel debe leer (y, si hace falta, re-encriptar) la contraseña en PAS_USU.
    // Sin esto, un hash bcrypt de otro "costo" hace que intente escribir en una columna "password" y da error 500.
    public function getAuthPasswordName()
    {
        return 'PAS_USU';
    }

    public function getAuthPassword()
    {
        return $this->PAS_USU;
    }

    // Tu tabla no tiene columna remember_token
    public function getRememberTokenName()
    {
        return '';
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'COD_ROL', 'COD_ROL');
    }
<<<<<<< HEAD
}
=======

    /**
     * ¿El usuario tiene alguno de estos roles?
     *   $usuario->tieneRol('administrador', 'supervisor')
     * Sin argumentos devuelve true (cualquier usuario con sesión).
     */
    public function tieneRol(string ...$roles): bool
    {
        if ($roles === []) {
            return true;
        }

        return in_array($this->rol?->DES_ROL, $roles, true);
    }
}
>>>>>>> main
