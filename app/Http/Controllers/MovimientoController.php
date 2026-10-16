<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'estacion'    => 'nullable|integer',
            'combustible' => 'nullable|integer',
            'tipo'        => 'nullable|in:entrada,salida',
        ]);

        // El encargado de estacion solo ve los movimientos de SU estacion
        $usuario = $request->user();
        $soloSuEstacion = $usuario
            && $usuario->tieneRol('encargado_estacion')
            && ! $usuario->tieneRol('administrador', 'administrador_sistema', 'supervisor');

        $movimientos = DB::table('movimiento_existencia as m')
            ->join('estacion as e', 'e.COD_EST', '=', 'm.COD_EST')
            ->join('tipo_combustible as t', 't.COD_COM', '=', 'm.COD_COM')
            ->leftJoin('usuario as u', 'u.COD_USU', '=', 'm.COD_USU')
            ->leftJoin('rol as r', 'r.COD_ROL', '=', 'u.COD_ROL')
            ->select(
                'm.COD_MOV', 'm.TIP_MOV', 'm.CAN_MOV', 'm.REF_MOV', 'm.FEC_MOV',
                'e.NOM_EST', 't.DES_COM', 'u.NOM_USU', 'r.DES_ROL'
            )
            ->when($soloSuEstacion, function ($q) use ($usuario) {
                $usuario->COD_EST
                    ? $q->where('m.COD_EST', $usuario->COD_EST)
                    : $q->whereRaw('1 = 0');
            })
            ->when($filtros['estacion'] ?? null, fn ($q, $v) => $q->where('m.COD_EST', $v))
            ->when($filtros['combustible'] ?? null, fn ($q, $v) => $q->where('m.COD_COM', $v))
            ->when($filtros['tipo'] ?? null, fn ($q, $v) => $q->where('m.TIP_MOV', $v))
            ->orderByDesc('m.FEC_MOV')
            ->orderByDesc('m.COD_MOV')
            ->limit(200)
            ->get();

        $estaciones = DB::table('estacion')
            ->when($soloSuEstacion, function ($q) use ($usuario) {
                $usuario->COD_EST
                    ? $q->where('COD_EST', $usuario->COD_EST)
                    : $q->whereRaw('1 = 0');
            })
            ->orderBy('NOM_EST')
            ->get(['COD_EST', 'NOM_EST']);

        $combustibles = DB::table('tipo_combustible')->orderBy('DES_COM')->get(['COD_COM', 'DES_COM']);

        return view('movimientos.index', compact('movimientos', 'estaciones', 'combustibles', 'filtros'));
    }
}