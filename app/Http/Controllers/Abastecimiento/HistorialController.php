<?php

namespace App\Http\Controllers\Abastecimiento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistorialController extends Controller
{
    public function index(Request $request)
    {
        $consulta = DB::table('historial_estado_abastecimiento as h')
            ->join('abastecimiento as a', 'a.COD_ABA', '=', 'h.COD_ABA')
            ->join('solicitud_abastecimiento as s', 's.COD_SOL', '=', 'a.COD_SOL')
            ->join('estacion as e', 'e.COD_EST', '=', 's.COD_EST')
            ->leftJoin('usuario as u', 'u.COD_USU', '=', 'h.COD_USU')
            ->select('h.*', 'e.NOM_EST', 'u.NOM_USU');

        $filtro = $request->query('abastecimiento');
        if ($filtro !== null && $filtro !== '' && ctype_digit((string) $filtro)) {
            $consulta->where('h.COD_ABA', (int) $filtro);
        } else {
            $filtro = null;
        }

        $items = $consulta->orderByDesc('h.COD_HIS')->limit(300)->get();
        return view('abastecimiento.historial.index', compact('items', 'filtro'));
    }
}