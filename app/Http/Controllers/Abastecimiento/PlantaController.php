<?php

namespace App\Http\Controllers\Abastecimiento;

use App\Http\Controllers\Controller;
use App\Models\PlantaDeposito;
use Illuminate\Http\Request;

class PlantaController extends Controller
{
    private function reglas(): array
    {
        return [
            'NOM_PLA' => ['required', 'string', 'max:120'],
            'UBI_PLA' => ['nullable', 'string', 'max:200'],
            'ACT_PLA' => ['nullable', 'boolean'],
        ];
    }

    public function index()
    {
        $items = PlantaDeposito::withCount('tanques')->orderBy('COD_PLA')->get();
        return view('abastecimiento.plantas.index', compact('items'));
    }

    public function create()
    {
        return view('abastecimiento.plantas.form');
    }

    public function store(Request $request)
    {
        $datos = $request->validate($this->reglas());
        $datos['ACT_PLA'] = $request->boolean('ACT_PLA');
        PlantaDeposito::create($datos);
        return redirect()->route('abast.plantas.index')->with('ok', 'Planta creada.');
    }

    public function edit(PlantaDeposito $planta)
    {
        return view('abastecimiento.plantas.form', ['item' => $planta]);
    }

    public function update(Request $request, PlantaDeposito $planta)
    {
        $datos = $request->validate($this->reglas());
        $datos['ACT_PLA'] = $request->boolean('ACT_PLA');
        $planta->update($datos);
        return redirect()->route('abast.plantas.index')->with('ok', 'Planta actualizada.');
    }

    public function destroy(PlantaDeposito $planta)
    {
        if ($planta->tanques()->exists()) {
            return back()->withErrors('No se puede eliminar: la planta tiene tanques. Desactivala en su lugar.');
        }
        $planta->delete();
        return redirect()->route('abast.plantas.index')->with('ok', 'Planta eliminada.');
    }
}