<?php

namespace App\Models;

use App\Models\Concerns\UsaHashid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomovilRegistro extends Model
{
    use UsaHashid;

    protected $table = 'automovil';

    protected $primaryKey = 'COD_VEH';

    public $timestamps = false;

    protected $fillable = [
        'PLA_VEH',
        'TIP_VEH',
        'MOD_VEH',
        'NOM_PROP',
        'TEL_PROP',
        'COD_EST',
    ];

    public function estacion(): BelongsTo
    {
        return $this->belongsTo(Estacion::class, 'COD_EST', 'COD_EST');
    }
}
