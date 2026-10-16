<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoCisterna extends Model
{
    protected $table = 'mantenimiento_cisterna';
    protected $primaryKey = 'COD_MAN';
    public $timestamps = false;

    protected $fillable = [
        'COD_CIS', 'TIP_MAN', 'DES_MAN', 'FEC_MAN',
    ];
}
