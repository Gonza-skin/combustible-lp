<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abastecimiento extends Model
{
    protected $table = 'abastecimiento';
    protected $primaryKey = 'COD_ABA';
    public $timestamps = false;

    // DIF_ABA es columna calculada: NO va en fillable
    protected $fillable = ['COD_SOL', 'COD_COM', 'COD_CIS', 'CAN_ABA', 'ESD_ABA', 'COD_USU'];
}