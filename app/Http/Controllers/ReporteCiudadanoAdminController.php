<?php

namespace App\Http\Controllers;

use App\Models\ReporteCiudadanoRegistro;
use Illuminate\View\View;

class ReporteCiudadanoAdminController extends Controller
{
    public function index(): View
    {
        $reportes = ReporteCiudadanoRegistro::query()
            ->join('estacion', 'estacion.COD_EST', '=', 'reporte_ciudadano.COD_EST')
            ->leftJoin('usuario', 'usuario.COD_USU', '=', 'reporte_ciudadano.COD_USU')
            ->orderByDesc('reporte_ciudadano.FEC_REP')
            ->orderByDesc('reporte_ciudadano.COD_REP')
            ->select([
                'reporte_ciudadano.*',
                'estacion.NOM_EST as ESTACION',
                'usuario.NOM_USU as CIUDADANO',
            ])
            ->paginate(20);

        return view('reportes-ciudadanos.index', ['reportes' => $reportes]);
    }
}
