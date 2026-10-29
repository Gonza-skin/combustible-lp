<?php

namespace App\Models;

use App\Models\Concerns\UsaHashid;
use Illuminate\Database\Eloquent\Model;

class SolicitudAbastecimiento extends Model
{
    use UsaHashid;

    protected $table = 'solicitud_abastecimiento';
    protected $primaryKey = 'COD_SOL';
    public $timestamps = false;

    protected $fillable = ['COD_EST', 'COD_COM', 'CAN_SOL', 'ESD_SOL', 'COD_USU'];
}