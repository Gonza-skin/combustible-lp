<?php

namespace App\Http\Controllers;

use App\Models\IncidenciaRegistro;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class IncidenciaController extends Controller
{
    private const TIPOS = [
        'retraso',
        'diferencia_combustible',
        'almacenamiento',
        'otro',
    ];

    private const ESTADOS = [
        'detectada',
        'en_revision',
        'resuelta',
        'cerrada',
    ];

    public function index(): View
    {
        $incidencias = IncidenciaRegistro::query()
            ->orderByDesc('FEC_INC')
            ->orderByDesc('COD_INC')
            ->paginate(15);

        return view('incidencias.index', ['incidencias' => $incidencias]);
    }

    public function create(): View
    {
        return view('incidencias.form', [
            'incidencia' => null,
            'abastecimientos' => $this->abastecimientos(),
            'tanques' => $this->tanques(),
            'tipos' => self::TIPOS,
            'estados' => self::ESTADOS,
            'titulo' => 'Registrar incidencia',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $incidencia = IncidenciaRegistro::create(
            $request->validate($this->rules())
                + ['COD_USU' => $this->usuario($request)->COD_USU]
        );

        return redirect()
            ->route('incidencias.show', $incidencia->COD_INC)
            ->with('success', 'La incidencia se registró correctamente.');
    }

    public function show(int $incidencia): View
    {
        return view('incidencias.show', [
            'incidencia' => IncidenciaRegistro::findOrFail($incidencia),
        ]);
    }

    public function edit(int $incidencia): View
    {
        return view('incidencias.form', [
            'incidencia' => IncidenciaRegistro::findOrFail($incidencia),
            'abastecimientos' => $this->abastecimientos(),
            'tanques' => $this->tanques(),
            'tipos' => self::TIPOS,
            'estados' => self::ESTADOS,
            'titulo' => 'Editar incidencia',
        ]);
    }

    public function update(Request $request, int $incidencia): RedirectResponse
    {
        $registro = IncidenciaRegistro::findOrFail($incidencia);
        $registro->update($request->validate($this->rules()));

        return redirect()
            ->route('incidencias.show', $registro->COD_INC)
            ->with('success', 'La incidencia se actualizó correctamente.');
    }

    public function destroy(int $incidencia): RedirectResponse
    {
        $registro = IncidenciaRegistro::findOrFail($incidencia);

        if (DB::table('alerta')->where('COD_INC', $registro->COD_INC)->exists()) {
            return back()->withErrors([
                'incidencia' => 'No se puede eliminar esta incidencia porque tiene alertas relacionadas.',
            ]);
        }

        $registro->delete();

        return redirect()
            ->route('incidencias.index')
            ->with('success', 'La incidencia se eliminó correctamente.');
    }

    private function abastecimientos()
    {
        return DB::table('abastecimiento')
            ->orderByDesc('COD_ABA')
            ->get(['COD_ABA', 'ESD_ABA']);
    }

    private function tanques()
    {
        return DB::table('tanque_almacenamiento')
            ->orderBy('NOM_TAN')
            ->get(['COD_TAN', 'NOM_TAN']);
    }

    private function usuario(Request $request): Usuario
    {
        $usuario = $request->user();
        abort_unless($usuario instanceof Usuario, 403);

        return $usuario;
    }

    private function rules(): array
    {
        return [
            'TIP_INC' => ['required', 'string', 'in:'.implode(',', self::TIPOS)],
            'COD_ABA' => ['nullable', 'integer', 'exists:abastecimiento,COD_ABA'],
            'COD_TAN' => ['nullable', 'integer', 'exists:tanque_almacenamiento,COD_TAN'],
            'DES_INC' => ['nullable', 'string'],
            'ESD_INC' => ['required', 'string', 'in:'.implode(',', self::ESTADOS)],
            'ACC_INC' => ['nullable', 'string'],
            'FRS_INC' => ['required_if:ESD_INC,resuelta,cerrada', 'nullable', 'date'],
        ];
    }
}
