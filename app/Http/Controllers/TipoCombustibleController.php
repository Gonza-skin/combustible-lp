<?php

namespace App\Http\Controllers;

use App\Models\TipoCombustible;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Catálogo de tipos de combustible (Gasolina y Diesel): se consulta y se puede corregir el nombre. */
class TipoCombustibleController extends Controller
{
    public function index(): View
    {
        $tipos = TipoCombustible::orderBy('COD_COM')->get();

        return view('tipos-combustible.index', compact('tipos'));
    }

    public function edit(TipoCombustible $tipo): View
    {
        return view('tipos-combustible.edit', compact('tipo'));
    }

    public function update(Request $request, TipoCombustible $tipo): RedirectResponse
    {
        $datos = $request->validate([
            'DES_COM' => [
                'required', 'string', 'max:30',
                Rule::unique('tipo_combustible', 'DES_COM')->ignore($tipo->COD_COM, 'COD_COM'),
            ],
        ]);

        $tipo->update($datos);

        return redirect()->route('tipos-combustible.index')->with('exito', 'Tipo de combustible actualizado.');
    }
}