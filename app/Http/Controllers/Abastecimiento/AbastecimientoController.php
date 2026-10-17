<?php

namespace App\Http\Controllers\Abastecimiento;

use App\Http\Controllers\Controller;
use App\Models\Abastecimiento;
use App\Models\HistorialEstadoAbastecimiento;
use App\Models\SolicitudAbastecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbastecimientoController extends Controller
{
    // Flujo permitido de estados
    private const FLUJO = [
        'pendiente'   => ['asignado', 'cancelado'],
        'asignado'    => ['en_transito', 'cancelado'],
        'en_transito' => ['entregado', 'cancelado'],
        'entregado'   => ['cerrado'],
        'cerrado'     => [],
        'cancelado'   => [],
    ];

    private function consulta()
    {
        return Abastecimiento::query()
            ->join('solicitud_abastecimiento', 'solicitud_abastecimiento.COD_SOL', '=', 'abastecimiento.COD_SOL')
            ->join('estacion', 'estacion.COD_EST', '=', 'solicitud_abastecimiento.COD_EST')
            ->join('tipo_combustible', 'tipo_combustible.COD_COM', '=', 'abastecimiento.COD_COM')
            ->leftJoin('cisterna', 'cisterna.COD_CIS', '=', 'abastecimiento.COD_CIS')
            ->select('abastecimiento.*', 'estacion.NOM_EST', 'tipo_combustible.DES_COM', 'cisterna.PLC_CIS');
    }

    public function index()
    {
        $items = $this->consulta()->orderByDesc('abastecimiento.COD_ABA')->get();
        $pendientes = SolicitudAbastecimiento::query()
            ->join('estacion', 'estacion.COD_EST', '=', 'solicitud_abastecimiento.COD_EST')
            ->join('tipo_combustible', 'tipo_combustible.COD_COM', '=', 'solicitud_abastecimiento.COD_COM')
            ->where('solicitud_abastecimiento.ESD_SOL', 'pendiente')
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))->from('abastecimiento')
                  ->whereColumn('abastecimiento.COD_SOL', 'solicitud_abastecimiento.COD_SOL');
            })
            ->select('solicitud_abastecimiento.*', 'estacion.NOM_EST', 'tipo_combustible.DES_COM')
            ->orderBy('solicitud_abastecimiento.COD_SOL')
            ->get();
        return view('abastecimiento.gestion.index', compact('items', 'pendientes'));
    }

    // Crear abastecimiento desde una solicitud pendiente
    public function crearDesdeSolicitud(Request $request, SolicitudAbastecimiento $solicitud)
    {
        try {
            DB::transaction(function () use ($request, $solicitud) {
                $sol = SolicitudAbastecimiento::lockForUpdate()->findOrFail($solicitud->COD_SOL);

                if ($sol->ESD_SOL !== 'pendiente') {
                    throw new \RuntimeException('La solicitud ya no está pendiente.');
                }
                if (Abastecimiento::where('COD_SOL', $sol->COD_SOL)->exists()) {
                    throw new \RuntimeException('Esa solicitud ya tiene un abastecimiento.');
                }

                $aba = Abastecimiento::create([
                    'COD_SOL' => $sol->COD_SOL,
                    'COD_COM' => $sol->COD_COM,
                    'CAN_ABA' => $sol->CAN_SOL,
                    'ESD_ABA' => 'pendiente',
                    'COD_USU' => $request->user()->COD_USU,
                ]);

                $sol->update(['ESD_SOL' => 'atendida']);

                HistorialEstadoAbastecimiento::create([
                    'COD_ABA' => $aba->COD_ABA,
                    'ESD_HIS' => 'pendiente',
                    'COD_USU' => $request->user()->COD_USU,
                    'OBS_HIS' => 'Abastecimiento creado desde la solicitud #' . $sol->COD_SOL,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('abast.gestion.index')->with('ok', 'Abastecimiento creado.');
    }

    public function show(Abastecimiento $abastecimiento)
    {
        $item = $this->consulta()->where('abastecimiento.COD_ABA', $abastecimiento->COD_ABA)->first();

        $cisternas = DB::table('cisterna')
            ->where('COD_COM', $item->COD_COM)
            ->where('ESD_CIS', 'disponible')
            ->orderBy('PLC_CIS')
            ->get();

        $siguientes = self::FLUJO[$item->ESD_ABA] ?? [];
        // Para pasar a asignado hace falta una cisterna
        if ($item->ESD_ABA === 'pendiente') {
            $siguientes = array_values(array_filter($siguientes, fn ($e) => $e !== 'asignado'));
        }

        $historial = DB::table('historial_estado_abastecimiento as h')
            ->leftJoin('usuario as u', 'u.COD_USU', '=', 'h.COD_USU')
            ->where('h.COD_ABA', $abastecimiento->COD_ABA)
            ->orderBy('h.COD_HIS')
            ->select('h.*', 'u.NOM_USU')
            ->get();

        return view('abastecimiento.gestion.show', compact('item', 'cisternas', 'siguientes', 'historial'));
    }

    // Asignar cisterna (pendiente -> asignado) o reasignar (asignado)
    public function asignarCisterna(Request $request, Abastecimiento $abastecimiento)
    {
        $datos = $request->validate([
            'COD_CIS' => ['required', 'integer', 'exists:cisterna,COD_CIS'],
            'OBS_HIS' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            DB::transaction(function () use ($request, $abastecimiento, $datos) {
                $aba = Abastecimiento::lockForUpdate()->findOrFail($abastecimiento->COD_ABA);

                if (!in_array($aba->ESD_ABA, ['pendiente', 'asignado'], true)) {
                    throw new \RuntimeException('Solo se puede asignar cisterna en estado pendiente o asignado.');
                }

                $cis = DB::table('cisterna')->where('COD_CIS', $datos['COD_CIS'])->lockForUpdate()->first();
                if ((int) $cis->COD_COM !== (int) $aba->COD_COM) {
                    throw new \RuntimeException('La cisterna no es del mismo combustible.');
                }
                if ($cis->ESD_CIS !== 'disponible') {
                    throw new \RuntimeException('La cisterna no está disponible.');
                }
                if ((float) $cis->CAP_CIS < (float) $aba->CAN_ABA) {
                    throw new \RuntimeException('La cisterna no tiene capacidad suficiente para esa cantidad.');
                }

                // Si había otra cisterna asignada, se libera
                if ($aba->COD_CIS && (int) $aba->COD_CIS !== (int) $cis->COD_CIS) {
                    DB::table('cisterna')->where('COD_CIS', $aba->COD_CIS)->update(['ESD_CIS' => 'disponible']);
                }

                DB::table('cisterna')->where('COD_CIS', $cis->COD_CIS)->update(['ESD_CIS' => 'asignada']);
                $aba->update(['COD_CIS' => $cis->COD_CIS, 'ESD_ABA' => 'asignado']);

                HistorialEstadoAbastecimiento::create([
                    'COD_ABA' => $aba->COD_ABA,
                    'ESD_HIS' => 'asignado',
                    'COD_USU' => $request->user()->COD_USU,
                    'OBS_HIS' => $datos['OBS_HIS'] ?? ('Cisterna asignada: ' . $cis->PLC_CIS),
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('abast.gestion.show', $abastecimiento->COD_ABA)->with('ok', 'Cisterna asignada.');
    }

    // Cambiar estado siguiendo el flujo
    public function cambiarEstado(Request $request, Abastecimiento $abastecimiento)
    {
        $datos = $request->validate([
            'ESD_ABA' => ['required', 'in:en_transito,entregado,cerrado,cancelado'],
            'OBS_HIS' => ['nullable', 'string', 'max:255'],
        ]);
        $nuevo = $datos['ESD_ABA'];

        try {
            DB::transaction(function () use ($request, $abastecimiento, $datos, $nuevo) {
                $aba = Abastecimiento::lockForUpdate()->findOrFail($abastecimiento->COD_ABA);

                if (!in_array($nuevo, self::FLUJO[$aba->ESD_ABA] ?? [], true)) {
                    throw new \RuntimeException("No se puede pasar de {$aba->ESD_ABA} a {$nuevo}.");
                }

                $cambios = ['ESD_ABA' => $nuevo];
                if ($nuevo === 'en_transito') {
                    $cambios['HSA_ABA'] = now();
                    if ($aba->COD_CIS) {
                        DB::table('cisterna')->where('COD_CIS', $aba->COD_CIS)->update(['ESD_CIS' => 'en_transito']);
                    }
                }
                if ($nuevo === 'entregado') {
                    $cambios['HRL_ABA'] = now();
                }
                if (in_array($nuevo, ['entregado', 'cancelado'], true) && $aba->COD_CIS) {
                    DB::table('cisterna')->where('COD_CIS', $aba->COD_CIS)->update(['ESD_CIS' => 'disponible']);
                }

                $aba->update($cambios);

                // Si se cancela, la solicitud también queda cancelada
                if ($nuevo === 'cancelado') {
                    SolicitudAbastecimiento::where('COD_SOL', $aba->COD_SOL)->update(['ESD_SOL' => 'cancelada']);
                }

                HistorialEstadoAbastecimiento::create([
                    'COD_ABA' => $aba->COD_ABA,
                    'ESD_HIS' => $nuevo,
                    'COD_USU' => $request->user()->COD_USU,
                    'OBS_HIS' => $datos['OBS_HIS'] ?? null,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('abast.gestion.show', $abastecimiento->COD_ABA)->with('ok', 'Estado actualizado.');
    }
}