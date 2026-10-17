<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    protected $table = 'tipo_combustible';
    protected $primaryKey = 'COD_COM';
    public $timestamps = false;

    protected $fillable = ['DES_COM'];
}