<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AutomovilAccess;
use App\Models\AutomovilRegistro;
use App\Models\Estacion;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AutomovilController extends Controller
{
    public function index(Request $request): View
    {
        $automoviles = $this->scopedAutomoviles($request)
            ->orderBy('PLA_VEH')
            ->paginate(15)
            ->withQueryString();

        return view('automoviles.index', [
            'automoviles' => $automoviles,
            'esAdministrador' => AutomovilAccess::isAdministrator($request->user()),
        ]);
    }

    public function create(): View
    {
        $this->assertAdministrator(request());

        return view('automoviles.form', [
            'automovil' => null,
            'estaciones' => $this->estaciones(),
            'titulo' => 'Registrar automóvil',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->assertAdministrator($request);

        AutomovilRegistro::create($request->validate($this->rules()));

        return redirect()
            ->route('automoviles.index')
            ->with('success', 'El automóvil se registró correctamente.');
    }

    public function show(Request $request, AutomovilRegistro $automovil): View
    {
        $registro = $this->scopedAutomoviles($request)->findOrFail($automovil->getKey());

        return view('automoviles.show', [
            'registro' => $registro,
            'esAdministrador' => AutomovilAccess::isAdministrator($request->user()),
        ]);
    }

    public function edit(AutomovilRegistro $automovil): View
    {
        $this->assertAdministrator(request());

        return view('automoviles.form', [
            'automovil' => $automovil,
            'estaciones' => $this->estaciones(),
            'titulo' => 'Editar automóvil',
        ]);
    }

    public function update(Request $request, AutomovilRegistro $automovil): RedirectResponse
    {
        $this->assertAdministrator($request);

        $registro = $automovil;
        $registro->update($request->validate($this->rules($registro)));

        return redirect()
            ->route('automoviles.show', $registro)
            ->with('success', 'El automóvil se actualizó correctamente.');
    }

    public function destroy(Request $request, AutomovilRegistro $automovil): RedirectResponse
    {
        $this->assertAdministrator($request);

        $registro = $automovil;

        if (DB::table('venta_combustible')->where('COD_VEH', $registro->COD_VEH)->exists()) {
            return back()->withErrors([
                'automovil' => 'No se puede eliminar este automóvil porque tiene ventas registradas.',
            ]);
        }

        $registro->delete();

        return redirect()
            ->route('automoviles.index')
            ->with('success', 'El automóvil se eliminó correctamente.');
    }

    private function scopedAutomoviles(Request $request): Builder
    {
        $query = AutomovilRegistro::query()->with('estacion');

        if (! AutomovilAccess::isAdministrator($request->user())) {
            $user = $request->user();
            abort_unless(AutomovilAccess::isStationManager($user), 403);
            abort_unless($user instanceof Usuario, 403);

            if ($user->COD_EST === null) {
                return $query->whereRaw('1 = 0');
            }

            $query->where('COD_EST', $user->COD_EST);
        }

        return $query;
    }

    private function assertAdministrator(Request $request): void
    {
        abort_unless(AutomovilAccess::isAdministrator($request->user()), 403);
    }

    private function estaciones()
    {
        return Estacion::query()->orderBy('NOM_EST')->get(['COD_EST', 'NOM_EST']);
    }

    private function rules(?AutomovilRegistro $registro = null): array
    {
        $plate = Rule::unique('automovil', 'PLA_VEH');

        if ($registro !== null) {
            $plate->ignore($registro->COD_VEH, 'COD_VEH');
        }

        return [
            'PLA_VEH' => ['required', 'string', 'max:15', $plate],
            'TIP_VEH' => ['required', 'string', 'max:20'],
            'MOD_VEH' => ['required', 'string', 'max:60'],
            'NOM_PROP' => ['nullable', 'string', 'max:150'],
            'TEL_PROP' => ['nullable', 'digits:9'],
            'COD_EST' => ['required', 'integer', 'exists:estacion,COD_EST'],
        ];
    }
}
