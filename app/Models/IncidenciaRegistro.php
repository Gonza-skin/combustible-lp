<?php

namespace App\Models;

use App\Models\Concerns\UsaHashid;
use Illuminate\Database\Eloquent\Model;

class IncidenciaRegistro extends Model
{
    use UsaHashid;

    protected $table = 'incidencia';

    protected $primaryKey = 'COD_INC';

    public $timestamps = false;

    protected $fillable = [
        'TIP_INC',
        'COD_ABA',
        'COD_TAN',
        'DES_INC',
        'ESD_INC',
        'COD_USU',
        'ACC_INC',
        'FRS_INC',
    ];
}
