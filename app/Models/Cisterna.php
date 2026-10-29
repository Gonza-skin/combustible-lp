<?php

namespace App\Models;

use App\Models\Concerns\UsaHashid;
use Illuminate\Database\Eloquent\Model;

class Cisterna extends Model
{
    use UsaHashid;

    protected $table = 'cisterna';
    protected $primaryKey = 'COD_CIS';
    public $timestamps = false;

    protected $fillable = [
        'PLC_CIS', 'CAP_CIS', 'COD_COM', 'COD_CON', 'ESD_CIS',
    ];
}
