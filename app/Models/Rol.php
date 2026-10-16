<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'COD_ROL';
    public $timestamps = false;

    protected $fillable = ['DES_ROL'];
}