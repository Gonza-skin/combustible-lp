<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlertaRegistro extends Model
{
    protected $table = 'alerta';

    protected $primaryKey = 'COD_ALE';

    public $timestamps = false;

    protected $fillable = [
        'TIP_ALE',
        'MEN_ALE',
        'NIV_ALE',
        'COD_EST',
        'COD_ABA',
        'COD_TAN',
        'COD_INC',
        'LEI_ALE',
    ];
}
