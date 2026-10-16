<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'conductor';
    protected $primaryKey = 'COD_CON';
    public $timestamps = false;

    protected $fillable = [
        'COD_USU', 'NOM_CON', 'CI_CON', 'LIC_CON',
        'CAT_LIC', 'TEL_CON', 'ACT_CON',
    ];
}
