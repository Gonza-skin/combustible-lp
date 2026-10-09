<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class EstacionController extends Controller
{
    private function reglas(): array
    {
        return [
            'NOM_EST' => 'required|string|max:120',
            'DIR_EST' => 'nullable|string|max:200',
            'ZON_EST' => 'nullable|string|max:80',
            'LAT_EST' => 'nullable|numeric|between:-90,90',
            'LON_EST' => 'nullable|numeric|between:-180,180',
            'MIN_EST' => 'required|numeric|min:0',
            'MAX_EST' => 'required|numeric|gte:MIN_EST',
            'TPT_EST' => 'required|integer|min:1',
            'ESD_EST' => 'required|in:normal,nivel_bajo,sin_combustible',
        ];
    }

    public function index()
    {
        $estaciones = Estacion::orderBy('NOM_EST')->get();
        return view('estaciones.index', compact('estaciones'));
    }

    public function create()
    {
        return view('estaciones.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());
        $datos['ACT_EST'] = $request->boolean('ACT_EST');

        Estacion::create($datos);

        return redirect()->route('estaciones.index')
            ->with('ok', 'Estación creada correctamente.');
    }

    public function edit(Estacion $estacion)
    {
        return view('estaciones.edit', compact('estacion'));
    }

    public function update(Request $request, Estacion $estacion)
    {
        $datos = $request->validate($this->reglas());
        $datos['ACT_EST'] = $request->boolean('ACT_EST');

        $estacion->update($datos);

        return redirect()->route('estaciones.index')
            ->with('ok', 'Estación actualizada correctamente.');
    }

    public function destroy(Estacion $estacion)
    {
        try {
            $estacion->delete();
        } catch (QueryException $e) {
            return redirect()->route('estaciones.index')
                ->with('error', 'No se puede eliminar: la estación tiene registros asociados. Desactívala en su lugar.');
        }

        return redirect()->route('estaciones.index')
            ->with('ok', 'Estación eliminada.');
    }
}