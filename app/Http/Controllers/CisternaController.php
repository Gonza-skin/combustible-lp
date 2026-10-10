<?php

namespace App\Http\Controllers;

use App\Models\Cisterna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class CisternaController extends Controller
{

    // mostrar cisternas registradas

    public function index()
    {
        $cisternas = Cisterna::query()
            ->leftJoin(
                'tipo_combustible',
                'cisterna.COD_COM',
                '=',
                'tipo_combustible.COD_COM'
            )
            ->leftJoin(
                'conductor',
                'cisterna.COD_CON',
                '=',
                'conductor.COD_CON'
            )
            ->select(
                'cisterna.*',
                'tipo_combustible.DES_COM',
                'conductor.NOM_CON'
            )
            ->orderBy('cisterna.COD_CIS', 'desc')
            ->get();

        return view('cisternas.index_cisterna', compact('cisternas'));
    }

    // mostrar el formulario para registrar una cisterna
    public function create()
    {
        $combustibles = DB::table('tipo_combustible')
            ->orderBy('DES_COM')
            ->get();

        $conductores = DB::table('conductor')
            ->where('ACT_CON', 1)
            ->orderBy('NOM_CON')
            ->get();

        return view(
            'cisternas.new_cisterna',
            compact('combustibles', 'conductores')
        );
    }

    // guardar una cisterna nueva
    public function store(Request $request)
    {
        $datos = $request->validate([
            'PLC_CIS' => [
                'required',
                'string',
                'max:20',
                'unique:cisterna,PLC_CIS',
            ],
            'CAP_CIS' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'COD_COM' => [
                'required',
                'integer',
                'exists:tipo_combustible,COD_COM',
            ],
            'COD_CON' => [
                'nullable',
                'integer',
                'exists:conductor,COD_CON',
            ],
            'ESD_CIS' => [
                'required',
                Rule::in([
                    'disponible',
                    'asignada',
                    'en_transito',
                    'mantenimiento',
                ]),
            ],
        ], [
            'PLC_CIS.required' => 'La placa es obligatoria.',
            'PLC_CIS.unique' => 'Ya existe una cisterna con esa placa.',
            'PLC_CIS.max' => 'La placa no puede superar los 20 caracteres.',
            'CAP_CIS.required' => 'La capacidad es obligatoria.',
            'CAP_CIS.numeric' => 'La capacidad debe ser numérica.',
            'CAP_CIS.gt' => 'La capacidad debe ser mayor que cero.',
            'COD_COM.required' => 'Debes seleccionar un combustible.',
            'COD_COM.exists' => 'El combustible seleccionado no existe.',
            'COD_CON.exists' => 'El conductor seleccionado no existe.',
            'ESD_CIS.required' => 'Debes seleccionar un estado.',
            'ESD_CIS.in' => 'El estado seleccionado no es válido.',
        ]);

        Cisterna::create($datos);

        return redirect()
            ->route('cisternas.index')
            ->with('success', 'Cisterna registrada correctamente.');
    }

    // mostrar el formulario para editar una cisterna

    public function edit(Cisterna $cisterna)
    {
        $combustibles = DB::table('tipo_combustible')
            ->orderBy('DES_COM')
            ->get();

        $conductores = DB::table('conductor')
            ->where(function ($query) use ($cisterna) {
                $query->where('ACT_CON', 1);

                if ($cisterna->COD_CON !== null) {
                    $query->orWhere(
                        'COD_CON',
                        $cisterna->COD_CON
                    );
                }
            })
            ->orderBy('NOM_CON')
            ->get();

        return view(
            'cisternas.update_cisterna',
            compact('cisterna', 'combustibles', 'conductores')
        );
    }

    // actualizar los datos de una cisterna
    public function update(Request $request, Cisterna $cisterna)
    {
        $datos = $request->validate([
            'PLC_CIS' => [
                'required',
                'string',
                'max:20',
                Rule::unique('cisterna', 'PLC_CIS')
                    ->ignore($cisterna->COD_CIS, 'COD_CIS'),
            ],
            'CAP_CIS' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'COD_COM' => [
                'required',
                'integer',
                'exists:tipo_combustible,COD_COM',
            ],
            'COD_CON' => [
                'nullable',
                'integer',
                'exists:conductor,COD_CON',
            ],
            'ESD_CIS' => [
                'required',
                Rule::in([
                    'disponible',
                    'asignada',
                    'en_transito',
                    'mantenimiento',
                ]),
            ],
        ], [
            'PLC_CIS.required' => 'La placa es obligatoria.',
            'PLC_CIS.unique' => 'Otra cisterna ya utiliza esa placa.',
            'CAP_CIS.required' => 'La capacidad es obligatoria.',
            'CAP_CIS.numeric' => 'La capacidad debe ser numérica.',
            'CAP_CIS.gt' => 'La capacidad debe ser mayor que cero.',
            'COD_COM.required' => 'Debes seleccionar un combustible.',
            'COD_COM.exists' => 'El combustible seleccionado no existe.',
            'COD_CON.exists' => 'El conductor seleccionado no existe.',
            'ESD_CIS.required' => 'Debes seleccionar un estado.',
            'ESD_CIS.in' => 'El estado seleccionado no es válido.',
        ]);

        $cisterna->update($datos);

        return redirect()
            ->route('cisternas.index_cisterna')
            ->with('success', 'Cisterna actualizada correctamente.');
    }

    // eliminar una cisterna si no tiene registros relacionados
    public function destroy(Cisterna $cisterna)
    {
        try {
            $cisterna->delete();

            return redirect()
                ->route('cisternas.index_cisterna')
                ->with('success', 'Cisterna eliminada correctamente.');
        } catch (QueryException $e) {
            return redirect()
                ->route('cisternas.index_cisterna')
                ->with(
                    'error',
                    'No se puede eliminar esta cisterna porque tiene registros relacionados, por ejemplo, mantenimientos o abastecimientos.'
                );
        }
    }
}
