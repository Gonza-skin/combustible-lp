<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Estacion extends Model
{
    protected $table = 'estacion';
    protected $primaryKey = 'COD_EST';
    public $timestamps = false;

    protected $fillable = [
        'NOM_EST', 'DIR_EST', 'ZON_EST', 'LAT_EST', 'LON_EST',
        'MIN_EST', 'MAX_EST', 'TPT_EST', 'ESD_EST', 'ACT_EST',
    ];
}