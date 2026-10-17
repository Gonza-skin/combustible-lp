<?php

namespace App\Http\Controllers;

use App\Models\AlertaRegistro;
use Illuminate\View\View;

class AlertaController extends Controller
{
    public function index(): View
    {
        $alertas = AlertaRegistro::query()
            ->leftJoin('estacion', 'estacion.COD_EST', '=', 'alerta.COD_EST')
            ->orderByDesc('alerta.FEC_ALE')
            ->orderByDesc('alerta.COD_ALE')
            ->select([
                'alerta.*',
                'estacion.NOM_EST as ESTACION',
            ])
            ->paginate(20);

        return view('alertas.index', ['alertas' => $alertas]);
    }
}
