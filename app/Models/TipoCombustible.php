<?php

namespace App\Models;

use App\Models\Concerns\UsaHashid;
use Illuminate\Database\Eloquent\Model;

class TipoCombustible extends Model
{
    use UsaHashid;

    protected $table = 'tipo_combustible';
    protected $primaryKey = 'COD_COM';
    public $timestamps = false;

    protected $fillable = ['DES_COM'];
}