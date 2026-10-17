<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaCombustibleRegistro extends Model
{
    protected $table = 'venta_combustible';

    protected $primaryKey = 'COD_VEN';

    public $timestamps = false;

    protected $fillable = [
        'COD_EST',
        'COD_VEH',
        'COD_COM',
        'CAN_VEN',
        'PRE_VEN',
        'COD_USU',
    ];
}
