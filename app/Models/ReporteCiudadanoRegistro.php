<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReporteCiudadanoRegistro extends Model
{
    protected $table = 'reporte_ciudadano';

    protected $primaryKey = 'COD_REP';

    public $timestamps = false;

    protected $fillable = [
        'COD_EST',
        'COD_USU',
        'FIL_REP',
        'DIS_REP',
        'TES_REP',
    ];
}
