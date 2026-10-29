<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class RecomendacionController extends Controller
{
    public function index()
    {
        $filas = DB::table('estacion as e')
            ->crossJoin('tipo_combustible as t')
            ->leftJoin('existencia_estacion as x', function ($join) {
                $join->on('x.COD_EST', '=', 'e.COD_EST')
                    ->on('x.COD_COM', '=', 't.COD_COM');
            })
            ->where('e.ACT_EST', 1)
            ->select(
                'e.COD_EST', 'e.NOM_EST', 'e.ZON_EST', 'e.MIN_EST', 'e.MAX_EST', 'e.TPT_EST',
                't.DES_COM',
                DB::raw('COALESCE(x."CAN_EXI", 0) as "CAN_EXI"')
            )
            ->get();

        $recomendaciones = $filas->map(function ($f) {
            $cantidad = (float) $f->CAN_EXI;
            $minimo = (float) $f->MIN_EST;
            $maximo = (float) $f->MAX_EST;

            if ($cantidad <= 0) {
                $prioridad = 'Crítica';
                $color = 'rojo';
                $orden = 1;
            } elseif ($cantidad <= $minimo) {
                $prioridad = 'Alta';
                $color = 'amarillo';
                $orden = 2;
            } elseif ($cantidad <= $minimo * 1.5) {
                $prioridad = 'Media';
                $color = 'azul';
                $orden = 3;
            } else {
                $prioridad = 'Sin necesidad';
                $color = 'verde';
                $orden = 4;
            }

            return (object) [
                'estacion' => $f->NOM_EST,
                'zona' => $f->ZON_EST,
                'combustible' => $f->DES_COM,
                'cantidad' => $cantidad,
                'porcentaje' => $maximo > 0 ? round($cantidad / $maximo * 100, 1) : null,
                'sugerida' => $maximo > 0 ? max($maximo - $cantidad, 0) : 0,
                'transito' => $f->TPT_EST,
                'prioridad' => $prioridad,
                'color' => $color,
                'orden' => $orden,
            ];
        })->sortBy([['orden', 'asc'], ['estacion', 'asc']])->values();

        $resumen = [
            'Crítica' => $recomendaciones->where('prioridad', 'Crítica')->count(),
            'Alta' => $recomendaciones->where('prioridad', 'Alta')->count(),
            'Media' => $recomendaciones->where('prioridad', 'Media')->count(),
        ];

        return view('recomendaciones.index', compact('recomendaciones', 'resumen'));
    }
}
