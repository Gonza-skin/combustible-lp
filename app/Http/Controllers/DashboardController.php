<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Models\Cisterna;
use App\Models\Incidencia;

class DashboardController extends Controller
{
    public function index()
    {
        $estacionesActivas   = Estacion::where('ACT_EST', 1)->count();
        $estacionesNivelBajo = Estacion::where('ESD_EST', 'nivel_bajo')->count();
        $cisternasEnTransito = Cisterna::where('ESD_CIS', 'en_transito')->count();
        $incidenciasAbiertas = Incidencia::whereNotIn('ESD_INC', ['cerrada'])->count();

        return view('dashboard', compact(
            'estacionesActivas',
            'estacionesNivelBajo',
            'cisternasEnTransito',
            'incidenciasAbiertas'
        ));
    }
}