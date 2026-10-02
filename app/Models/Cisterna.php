<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cisterna extends Model
{
    protected $table = 'cisterna';
    protected $primaryKey = 'COD_CIS';
    public $timestamps = false;

    protected $fillable = [
        'PLC_CIS', 'CAP_CIS', 'COD_COM', 'COD_CON', 'ESD_CIS',
    ];
}