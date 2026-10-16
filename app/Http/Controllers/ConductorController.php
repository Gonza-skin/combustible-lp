<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConductorController extends Controller
{
    public function index(): View
    {
        $conductores = Conductor::query()
            ->leftJoin('usuario', 'conductor.COD_USU', '=', 'usuario.COD_USU')
            ->select('conductor.*', 'usuario.EML_USU')
            ->orderBy('conductor.COD_CON', 'desc')->get();

        return view('conductores.index_conductores', compact('conductores'));
    }

    public function create(): View
    {
        // Un usuario solo puede estar vinculado a un conductor (COD_USU es UNIQUE).
        $usuarios = DB::table('usuario')->whereNotIn('COD_USU', function ($query) {
            $query->select('COD_USU')->from('conductor');
        })->orderBy('NOM_USU')->get();

        return view('conductores.new_conductores', compact('usuarios'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'COD_USU' => 'required|integer|exists:usuario,COD_USU|unique:conductor,COD_USU',
            'NOM_CON' => 'required|string|max:150',
            'CI_CON' => 'required|string|max:20|unique:conductor,CI_CON',
            'LIC_CON' => 'required|string|max:20',
            'CAT_LIC' => 'nullable|string|max:10',
            'TEL_CON' => ['nullable', 'string', 'size:9', 'regex:/^[0-9]{9}$/'],
            'ACT_CON' => 'required|boolean',
        ];
        $messages = [
            'COD_USU.required' => 'Debes seleccionar un usuario del sistema.', 'COD_USU.exists' => 'El usuario seleccionado no existe.', 'COD_USU.unique' => 'Este usuario ya está vinculado a otro conductor.',
            'NOM_CON.required' => 'El nombre completo es obligatorio.', 'NOM_CON.max' => 'El nombre no debe superar los 150 caracteres.',
            'CI_CON.required' => 'La cédula de identidad es obligatoria.', 'CI_CON.unique' => 'Ya existe un conductor con esa cédula.', 'CI_CON.max' => 'La cédula no debe superar los 20 caracteres.',
            'LIC_CON.required' => 'El número de licencia es obligatorio.', 'LIC_CON.max' => 'La licencia no debe superar los 20 caracteres.',
            'CAT_LIC.max' => 'La categoría no debe superar los 10 caracteres.',
            'TEL_CON.size' => 'El teléfono debe contener exactamente 9 dígitos.', 'TEL_CON.regex' => 'El teléfono solo debe contener números.',
            'ACT_CON.required' => 'Debes indicar si el conductor está activo.', 'ACT_CON.boolean' => 'El estado seleccionado no es válido.',
        ];
        $datos = $request->validate($rules, $messages);
        $datos['ACT_CON'] = (int) $datos['ACT_CON'];

        try {
            Conductor::create($datos);
            return redirect()->route('conductores.index')->with('success', 'Conductor registrado correctamente.');
        } catch (QueryException $e) {
            Log::error('Error al registrar conductor: '.$e->getMessage());
            return back()->withInput()->with('error', 'No se pudo registrar el conductor. Verifica los datos y el usuario seleccionado.');
        }
    }

    public function show($codigo): View
    {
        $conductor = Conductor::query()->leftJoin('usuario', 'conductor.COD_USU', '=', 'usuario.COD_USU')
            ->select('conductor.*', 'usuario.NOM_USU as USUARIO_NOMBRE', 'usuario.EML_USU')
            ->where('conductor.COD_CON', $codigo)->firstOrFail();
        return view('conductores.show_conductores', compact('conductor'));
    }

    public function edit($codigo): View
    {
        $conductor = Conductor::findOrFail($codigo);
        $usuarios = DB::table('usuario')->where(function ($query) use ($conductor) {
            $query->whereNotIn('COD_USU', function ($subquery) {
                $subquery->select('COD_USU')->from('conductor');
            })->orWhere('COD_USU', $conductor->COD_USU);
        })->orderBy('NOM_USU')->get();
        return view('conductores.update_conductores', compact('conductor', 'usuarios'));
    }

    public function update(Request $request, $codigo): RedirectResponse
    {
        $conductor = Conductor::findOrFail($codigo);
        $rules = [
            'COD_USU' => ['required', 'integer', 'exists:usuario,COD_USU', Rule::unique('conductor', 'COD_USU')->ignore($conductor->COD_CON, 'COD_CON')],
            'NOM_CON' => 'required|string|max:150',
            'CI_CON' => ['required', 'string', 'max:20', Rule::unique('conductor', 'CI_CON')->ignore($conductor->COD_CON, 'COD_CON')],
            'LIC_CON' => 'required|string|max:20', 'CAT_LIC' => 'nullable|string|max:10',
            'TEL_CON' => ['nullable', 'string', 'size:9', 'regex:/^[0-9]{9}$/'], 'ACT_CON' => 'required|boolean',
        ];
        $messages = [
            'COD_USU.required' => 'Debes seleccionar un usuario del sistema.', 'COD_USU.exists' => 'El usuario seleccionado no existe.', 'COD_USU.unique' => 'Este usuario ya está vinculado a otro conductor.',
            'NOM_CON.required' => 'El nombre completo es obligatorio.', 'CI_CON.required' => 'La cédula de identidad es obligatoria.', 'CI_CON.unique' => 'Otro conductor ya utiliza esa cédula.',
            'LIC_CON.required' => 'El número de licencia es obligatorio.', 'TEL_CON.size' => 'El teléfono debe contener exactamente 9 dígitos.', 'TEL_CON.regex' => 'El teléfono solo debe contener números.',
        ];
        $datos = $request->validate($rules, $messages);
        $datos['ACT_CON'] = (int) $datos['ACT_CON'];

        try {
            $conductor->update($datos);
            return redirect()->route('conductores.index')->with('success', 'Conductor actualizado correctamente.');
        } catch (QueryException $e) {
            Log::error('Error al actualizar conductor: '.$e->getMessage());
            return back()->withInput()->with('error', 'No se pudo actualizar el conductor.');
        }
    }

    public function destroy($codigo): RedirectResponse
    {
        $conductor = Conductor::findOrFail($codigo);
        try {
            // La base impide borrar un conductor asignado a una cisterna.
            $conductor->delete();
            return redirect()->route('conductores.index')->with('success', 'Conductor eliminado correctamente.');
        } catch (QueryException $e) {
            return redirect()->route('conductores.index')->with('error', 'No se puede eliminar este conductor porque está asignado a una cisterna. Desasígnalo primero.');
        }
    }
}
