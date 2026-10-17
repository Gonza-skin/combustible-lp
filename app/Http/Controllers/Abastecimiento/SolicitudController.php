<?php

namespace App\Http\Controllers\Abastecimiento;

use App\Http\Controllers\Controller;
use App\Models\SolicitudAbastecimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    private function rol(Request $request): ?string
    {
        return DB::table('rol')->where('COD_ROL', $request->user()->COD_ROL)->value('DES_ROL');
    }

    private function consulta()
    {
        return SolicitudAbastecimiento::query()
            ->join('estacion', 'estacion.COD_EST', '=', 'solicitud_abastecimiento.COD_EST')
            ->join('tipo_combustible', 'tipo_combustible.COD_COM', '=', 'solicitud_abastecimiento.COD_COM')
            ->select('solicitud_abastecimiento.*', 'estacion.NOM_EST', 'tipo_combustible.DES_COM');
    }

    // El encargado solo puede tocar solicitudes de su estación
    private function autorizar(Request $request, SolicitudAbastecimiento $solicitud): void
    {
        if ($this->rol($request) === 'encargado_estacion'
            && (int) $solicitud->COD_EST !== (int) $request->user()->COD_EST) {
            abort(403, 'Esa solicitud no es de tu estación.');
        }
    }

    public function index(Request $request)
    {
        $consulta = $this->consulta();
        if ($this->rol($request) === 'encargado_estacion') {
            $consulta->where('solicitud_abastecimiento.COD_EST', $request->user()->COD_EST);
        }
        $items = $consulta->orderByDesc('solicitud_abastecimiento.COD_SOL')->get();
        $esEncargado = $this->rol($request) === 'encargado_estacion';
        return view('abastecimiento.solicitudes.index', compact('items', 'esEncargado'));
    }

    public function create(Request $request)
    {
        $codEst = $request->user()->COD_EST;
        if (!$codEst) {
            return redirect()->route('abast.solicitudes.index')
                ->withErrors('Tu usuario no tiene una estación asignada.');
        }
        return view('abastecimiento.solicitudes.create', [
            'estacion'     => DB::table('estacion')->where('COD_EST', $codEst)->value('NOM_EST'),
            'combustibles' => DB::table('tipo_combustible')->orderBy('DES_COM')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $codEst = $request->user()->COD_EST;
        if (!$codEst) {
            return back()->withErrors('Tu usuario no tiene una estación asignada.');
        }

        $datos = $request->validate([
            'COD_COM' => ['required', 'integer', 'exists:tipo_combustible,COD_COM'],
            'CAN_SOL' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
        ]);

        SolicitudAbastecimiento::create([
            'COD_EST' => $codEst,
            'COD_COM' => $datos['COD_COM'],
            'CAN_SOL' => $datos['CAN_SOL'],
            'ESD_SOL' => 'pendiente',
            'COD_USU' => $request->user()->COD_USU,
        ]);

        return redirect()->route('abast.solicitudes.index')->with('ok', 'Solicitud creada.');
    }

    public function show(Request $request, SolicitudAbastecimiento $solicitud)
    {
        $this->autorizar($request, $solicitud);
        $item = $this->consulta()->where('solicitud_abastecimiento.COD_SOL', $solicitud->COD_SOL)->first();
        $solicitante = DB::table('usuario')->where('COD_USU', $solicitud->COD_USU)->value('NOM_USU');
        return view('abastecimiento.solicitudes.show', compact('item', 'solicitante'));
    }

    public function cancelar(Request $request, SolicitudAbastecimiento $solicitud)
    {
        $this->autorizar($request, $solicitud);

        if ($solicitud->ESD_SOL !== 'pendiente') {
            return back()->withErrors('Solo se pueden cancelar solicitudes pendientes.');
        }

        $solicitud->update(['ESD_SOL' => 'cancelada']);
        return redirect()->route('abast.solicitudes.index')->with('ok', 'Solicitud cancelada.');
    }

    public function edit(Request $request, SolicitudAbastecimiento $solicitud)
    {
        $this->autorizar($request, $solicitud);
        if ($solicitud->ESD_SOL !== 'pendiente') {
            return redirect()->route('abast.solicitudes.index')->withErrors('Solo se pueden editar solicitudes pendientes.');
        }
        return view('abastecimiento.solicitudes.edit', [
            'item'         => $solicitud,
            'estacion'     => DB::table('estacion')->where('COD_EST', $solicitud->COD_EST)->value('NOM_EST'),
            'combustibles' => DB::table('tipo_combustible')->orderBy('DES_COM')->get(),
        ]);
    }

    public function update(Request $request, SolicitudAbastecimiento $solicitud)
    {
        $this->autorizar($request, $solicitud);
        if ($solicitud->ESD_SOL !== 'pendiente') {
            return redirect()->route('abast.solicitudes.index')->withErrors('Solo se pueden editar solicitudes pendientes.');
        }
        $datos = $request->validate([
            'COD_COM' => ['required', 'integer', 'exists:tipo_combustible,COD_COM'],
            'CAN_SOL' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
        ]);
        $solicitud->update($datos);
        return redirect()->route('abast.solicitudes.index')->with('ok', 'Solicitud actualizada.');
    }}