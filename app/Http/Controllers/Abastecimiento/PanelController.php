<?php

namespace App\Http\Controllers\Abastecimiento;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function index()
    {
        $datos = [
            'estacionesActivas'      => DB::table('estacion')->where('ACT_EST', 1)->count(),
            'estacionesNivelBajo'    => DB::table('estacion')->where('ESD_EST', 'nivel_bajo')->count(),
            'cisternasEnTransito'    => DB::table('cisterna')->where('ESD_CIS', 'en_transito')->count(),
            'incidenciasAbiertas'    => DB::table('incidencia')->whereNotIn('ESD_INC', ['cerrada'])->count(),
            'solicitudesPendientes'  => DB::table('solicitud_abastecimiento')->where('ESD_SOL', 'pendiente')->count(),
            'abastecimientosActivos' => DB::table('abastecimiento')->whereNotIn('ESD_ABA', ['cerrado', 'cancelado'])->count(),
        ];
        return view('abastecimiento.panel', $datos);
    }
}