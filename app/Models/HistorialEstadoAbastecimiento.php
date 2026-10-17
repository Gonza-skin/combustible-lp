<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialEstadoAbastecimiento extends Model
{
    protected $table = 'historial_estado_abastecimiento';
    protected $primaryKey = 'COD_HIS';
    public $timestamps = false;

    protected $fillable = ['COD_ABA', 'ESD_HIS', 'COD_USU', 'OBS_HIS'];
}