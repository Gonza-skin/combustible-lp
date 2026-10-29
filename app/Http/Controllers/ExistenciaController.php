<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Models\TipoCombustible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExistenciaController extends Controller
{
    private function consulta()
    {
        $query = DB::table('estacion as e')
            ->crossJoin('tipo_combustible as t')
            ->leftJoin('existencia_estacion as x', function ($join) {
                $join->on('x.COD_EST', '=', 'e.COD_EST')
                    ->on('x.COD_COM', '=', 't.COD_COM');
            })
            ->select(
                'e.COD_EST', 'e.NOM_EST', 'e.MIN_EST', 'e.MAX_EST', 'e.ESD_EST',
                't.COD_COM', 't.DES_COM',
                DB::raw('COALESCE(x."CAN_EXI", 0) as "CAN_EXI"'),
                'x.FEC_EXI'
            );

        // El encargado de estacion solo ve y ajusta SU estacion
        $usuario = request()->user();
        if ($usuario
            && $usuario->tieneRol('encargado_estacion')
            && ! $usuario->tieneRol('administrador', 'administrador_sistema')) {
            if ($usuario->COD_EST) {
                $query->where('e.COD_EST', $usuario->COD_EST);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        return $query;
    }

    private function buscarFila(int $estacion, int $combustible)
    {
        $fila = $this->consulta()
            ->where('e.COD_EST', $estacion)
            ->where('t.COD_COM', $combustible)
            ->first();

        abort_if($fila === null, 404);

        return $fila;
    }

    public function index()
    {
        $filas = $this->consulta()
            ->where('e.ACT_EST', 1)
            ->orderBy('e.NOM_EST')
            ->orderBy('t.DES_COM')
            ->get();

        return view('existencias.index', compact('filas'));
    }

    public function ajustar(string $estacion, string $combustible)
    {
        [$estacionId, $combustibleId] = $this->decodificarIds($estacion, $combustible);
        $fila = $this->buscarFila($estacionId, $combustibleId);

        return view('existencias.ajustar', compact('fila'));
    }

    public function guardar(Request $request, string $estacion, string $combustible)
    {
        [$estacionId, $combustibleId] = $this->decodificarIds($estacion, $combustible);
        $fila = $this->buscarFila($estacionId, $combustibleId);

        $datos = $request->validate([
            'CANTIDAD' => 'required|numeric|min:0|max:99999999',
            'MOTIVO' => 'required|string|max:120',
        ]);

        $nueva = round((float) $datos['CANTIDAD'], 2);

        if ((float) $fila->MAX_EST > 0 && $nueva > (float) $fila->MAX_EST) {
            return back()
                ->withErrors(['CANTIDAD' => 'La cantidad no puede superar el máximo de la estación ('.number_format($fila->MAX_EST, 2).' L).'])
                ->withInput();
        }

        $usuarioId = $request->user()?->COD_USU;

        DB::transaction(function () use ($estacionId, $combustibleId, $nueva, $datos, $usuarioId) {
            $actual = DB::table('existencia_estacion')
                ->where('COD_EST', $estacionId)
                ->where('COD_COM', $combustibleId)
                ->lockForUpdate()
                ->value('CAN_EXI');

            $anterior = $actual === null ? 0.0 : (float) $actual;
            $diferencia = round($nueva - $anterior, 2);

            if ($actual === null) {
                DB::table('existencia_estacion')->insert([
                    'COD_EST' => $estacionId,
                    'COD_COM' => $combustibleId,
                    'CAN_EXI' => $nueva,
                ]);
            } else {
                DB::table('existencia_estacion')
                    ->where('COD_EST', $estacionId)
                    ->where('COD_COM', $combustibleId)
                    ->update(['CAN_EXI' => $nueva]);
            }

            if ($diferencia != 0.0) {
                DB::table('movimiento_existencia')->insert([
                    'COD_EST' => $estacionId,
                    'COD_COM' => $combustibleId,
                    'TIP_MOV' => $diferencia > 0 ? 'entrada' : 'salida',
                    'CAN_MOV' => abs($diferencia),
                    'COD_USU' => $usuarioId,
                    'REF_MOV' => 'Ajuste: '.$datos['MOTIVO'],
                ]);
            }
        });

        return redirect()->route('existencias.index')
            ->with('ok', 'Existencia ajustada correctamente.');
    }

    private function decodificarIds(string $estacion, string $combustible): array
    {
        $estacionId = Estacion::decodeRouteKey($estacion);
        $combustibleId = TipoCombustible::decodeRouteKey($combustible);

        abort_if($estacionId === null || $combustibleId === null, 404);

        return [$estacionId, $combustibleId];
    }
}
