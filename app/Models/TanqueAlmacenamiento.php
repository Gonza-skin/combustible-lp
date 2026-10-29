<?php

namespace App\Models;

use App\Models\Concerns\UsaHashid;
use Illuminate\Database\Eloquent\Model;

class TanqueAlmacenamiento extends Model
{
    use UsaHashid;

    protected $table = 'tanque_almacenamiento';
    protected $primaryKey = 'COD_TAN';
    public $timestamps = false;

    protected $fillable = ['COD_PLA', 'COD_COM', 'NOM_TAN', 'CAP_TAN', 'NIV_TAN', 'ESD_TAN'];

    public function planta()
    {
        return $this->belongsTo(PlantaDeposito::class, 'COD_PLA', 'COD_PLA');
    }
}