<?php

namespace App\Http\Controllers;

use App\Models\MantenimientoCisterna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MantenimientoCisternaController extends Controller
{
    public function index(): View
    {
        $mantenimientos = MantenimientoCisterna::query()
            ->join('cisterna', 'mantenimiento_cisterna.COD_CIS', '=', 'cisterna.COD_CIS')
            ->select('mantenimiento_cisterna.*', 'cisterna.PLC_CIS')
            ->orderBy('mantenimiento_cisterna.FEC_MAN', 'desc')
            ->orderBy('mantenimiento_cisterna.COD_MAN', 'desc')->get();
        return view('mantenimiento-cisternas.index_man_cis', compact('mantenimientos'));
    }

    public function create(): View
    {
        $cisternas = DB::table('cisterna')->orderBy('PLC_CIS')->get();
        return view('mantenimiento-cisternas.new_man_cis', compact('cisternas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'COD_CIS' => 'required|integer|exists:cisterna,COD_CIS',
            'TIP_MAN' => ['required', Rule::in(['preventivo', 'correctivo', 'documentacion'])],
            'DES_MAN' => 'nullable|string',
            'FEC_MAN' => 'required|date|before_or_equal:today',
        ];
        $messages = [
            'COD_CIS.required' => 'Debes seleccionar una cisterna.', 'COD_CIS.exists' => 'La cisterna seleccionada no existe.',
            'TIP_MAN.required' => 'Debes seleccionar el tipo de mantenimiento.', 'TIP_MAN.in' => 'El tipo de mantenimiento no es válido.',
            'DES_MAN.string' => 'La descripción debe ser texto.', 'FEC_MAN.required' => 'La fecha de mantenimiento es obligatoria.',
            'FEC_MAN.date' => 'La fecha ingresada no es válida.', 'FEC_MAN.before_or_equal' => 'La fecha no puede ser posterior al día de hoy.',
        ];
        $datos = $request->validate($rules, $messages);
        try {
            MantenimientoCisterna::create($datos);
            return redirect()->route('mantenimiento-cisternas.index')->with('success', 'Mantenimiento registrado correctamente.');
        } catch (QueryException $e) {
            Log::error('Error al registrar mantenimiento: '.$e->getMessage());
            return back()->withInput()->with('error', 'No se pudo registrar el mantenimiento.');
        }
    }

    public function show(MantenimientoCisterna $mantenimiento_cisterna): View
    {
        $codigo = $mantenimiento_cisterna->getKey();
        $mantenimiento = MantenimientoCisterna::query()
            ->join('cisterna', 'mantenimiento_cisterna.COD_CIS', '=', 'cisterna.COD_CIS')
            ->select('mantenimiento_cisterna.*', 'cisterna.PLC_CIS', 'cisterna.CAP_CIS')
            ->where('mantenimiento_cisterna.COD_MAN', $codigo)->firstOrFail();
        return view('mantenimiento-cisternas.show_man_cis', compact('mantenimiento'));
    }

    public function edit(MantenimientoCisterna $mantenimiento_cisterna): View
    {
        $mantenimiento = $mantenimiento_cisterna;
        $cisternas = DB::table('cisterna')->orderBy('PLC_CIS')->get();
        return view('mantenimiento-cisternas.update_man_cis', compact('mantenimiento', 'cisternas'));
    }

    public function update(Request $request, MantenimientoCisterna $mantenimiento_cisterna): RedirectResponse
    {
        $mantenimiento = $mantenimiento_cisterna;
        $rules = [
            'COD_CIS' => 'required|integer|exists:cisterna,COD_CIS',
            'TIP_MAN' => ['required', Rule::in(['preventivo', 'correctivo', 'documentacion'])],
            'DES_MAN' => 'nullable|string',
            'FEC_MAN' => 'required|date|before_or_equal:today',
        ];
        $messages = [
            'COD_CIS.required' => 'Debes seleccionar una cisterna.', 'COD_CIS.exists' => 'La cisterna seleccionada no existe.',
            'TIP_MAN.required' => 'Debes seleccionar el tipo de mantenimiento.', 'TIP_MAN.in' => 'El tipo de mantenimiento no es válido.',
            'FEC_MAN.required' => 'La fecha de mantenimiento es obligatoria.', 'FEC_MAN.date' => 'La fecha ingresada no es válida.',
            'FEC_MAN.before_or_equal' => 'La fecha no puede ser posterior al día de hoy.',
        ];
        $datos = $request->validate($rules, $messages);
        try {
            $mantenimiento->update($datos);
            return redirect()->route('mantenimiento-cisternas.index')->with('success', 'Mantenimiento actualizado correctamente.');
        } catch (QueryException $e) {
            Log::error('Error al actualizar mantenimiento: '.$e->getMessage());
            return back()->withInput()->with('error', 'No se pudo actualizar el mantenimiento.');
        }
    }

    public function destroy(MantenimientoCisterna $mantenimiento_cisterna): RedirectResponse
    {
        $mantenimiento = $mantenimiento_cisterna;
        try {
            $mantenimiento->delete();
            return redirect()->route('mantenimiento-cisternas.index')->with('success', 'Registro de mantenimiento eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('mantenimiento-cisternas.index')->with('error', 'No se pudo eliminar el registro de mantenimiento.');
        }
    }
}
