<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';
    protected $primaryKey = 'COD_USU';
    public $timestamps = false;

    protected $fillable = [
        'NOM_USU', 'EML_USU', 'PAS_USU', 'COD_ROL', 'COD_EST', 'ACT_USU',
    ];

    protected $hidden = ['PAS_USU'];

    // Laravel debe leer la contraseña de PAS_USU
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
}