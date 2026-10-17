<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Models\ReporteCiudadanoRegistro;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteCiudadanoController extends Controller
{
    public function create(): View
    {
        return view('reportes-ciudadanos.create', [
            'estaciones' => Estacion::query()
                ->where('ACT_EST', true)
                ->orderBy('NOM_EST')
                ->get(['COD_EST', 'NOM_EST', 'ZON_EST']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $usuario = $request->user();
        abort_unless($usuario instanceof Usuario, 403);

        $data = $request->validate([
            'COD_EST' => ['required', 'integer', 'exists:estacion,COD_EST'],
            'FIL_REP' => ['nullable', 'in:sin_fila,corta,moderada,larga'],
            'DIS_REP' => ['nullable', 'in:disponible,poco_stock,agotado'],
            'TES_REP' => ['nullable', 'integer', 'min:0'],
        ]);

        ReporteCiudadanoRegistro::create($data + [
            'COD_USU' => $usuario->COD_USU,
        ]);

        return redirect()
            ->route('reportes-ciudadanos.create')
            ->with('success', 'Tu reporte ciudadano se registró correctamente.');
    }
}
