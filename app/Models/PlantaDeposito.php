<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantaDeposito extends Model
{
    protected $table = 'planta_deposito';
    protected $primaryKey = 'COD_PLA';
    public $timestamps = false;

    protected $fillable = ['NOM_PLA', 'UBI_PLA', 'ACT_PLA'];
    protected $casts = ['ACT_PLA' => 'boolean'];

    public function tanques()
    {
        return $this->hasMany(TanqueAlmacenamiento::class, 'COD_PLA', 'COD_PLA');
    }
}