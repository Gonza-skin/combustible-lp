<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\VentaCombustibleRegistro;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VentaCombustibleController extends Controller
{
    public function index(Request $request): View
    {
        $ventas = $this->ventasDeEstacion($request)
            ->orderByDesc('venta_combustible.FEC_VEN')
            ->orderByDesc('venta_combustible.COD_VEN')
            ->paginate(15)
            ->withQueryString();

        return view('ventas.index', ['ventas' => $ventas]);
    }

    public function create(Request $request): View
    {
        $usuario = $this->usuarioConEstacion($request);

        return view('ventas.form', [
            'venta' => null,
            'vehiculos' => $this->vehiculosDeEstacion($usuario->COD_EST),
            'combustibles' => $this->combustibles(),
            'titulo' => 'Registrar venta',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $usuario = $this->usuarioConEstacion($request);
        $venta = VentaCombustibleRegistro::create(
            $this->validatedData($request, $usuario)
                + [
                    'COD_EST' => $usuario->COD_EST,
                    'COD_USU' => $usuario->COD_USU,
                ]
        );

        return redirect()
            ->route('ventas.show', $venta)
            ->with('success', 'La venta se registró correctamente.');
    }

    public function show(Request $request, VentaCombustibleRegistro $venta): View
    {
        return view('ventas.show', [
            'venta' => $this->ventasDeEstacion($request)->findOrFail($venta->getKey()),
        ]);
    }

    public function edit(Request $request, VentaCombustibleRegistro $venta): View
    {
        $usuario = $this->usuarioConEstacion($request);

        return view('ventas.form', [
            'venta' => $this->ventasDeEstacion($request)->findOrFail($venta->getKey()),
            'vehiculos' => $this->vehiculosDeEstacion($usuario->COD_EST),
            'combustibles' => $this->combustibles(),
            'titulo' => 'Editar venta',
        ]);
    }

    public function update(Request $request, VentaCombustibleRegistro $venta): RedirectResponse
    {
        $usuario = $this->usuarioConEstacion($request);
        $registro = $this->ventasDeEstacion($request)->findOrFail($venta->getKey());
        $registro->update($this->validatedData($request, $usuario));

        return redirect()
            ->route('ventas.show', $registro)
            ->with('success', 'La venta se actualizó correctamente.');
    }

    public function destroy(Request $request, VentaCombustibleRegistro $venta): RedirectResponse
    {
        $registro = $this->ventasDeEstacion($request)->findOrFail($venta->getKey());
        $registro->delete();

        return redirect()
            ->route('ventas.index')
            ->with('success', 'La venta se eliminó correctamente.');
    }

    private function ventasDeEstacion(Request $request): Builder
    {
        $usuario = $request->user();
        abort_unless($usuario instanceof Usuario, 403);

        return VentaCombustibleRegistro::query()
            ->join('estacion', 'estacion.COD_EST', '=', 'venta_combustible.COD_EST')
            ->join('automovil', 'automovil.COD_VEH', '=', 'venta_combustible.COD_VEH')
            ->join('tipo_combustible', 'tipo_combustible.COD_COM', '=', 'venta_combustible.COD_COM')
            ->join('usuario', 'usuario.COD_USU', '=', 'venta_combustible.COD_USU')
            ->where('venta_combustible.COD_EST', $usuario->COD_EST)
            ->select([
                'venta_combustible.*',
                'estacion.NOM_EST as ESTACION',
                'automovil.PLA_VEH as PLACA',
                'tipo_combustible.DES_COM as COMBUSTIBLE',
                'usuario.NOM_USU as USUARIO',
            ]);
    }

    private function usuarioConEstacion(Request $request): Usuario
    {
        $usuario = $request->user();
        abort_unless($usuario instanceof Usuario && $usuario->COD_EST !== null, 403);

        return $usuario;
    }

    private function vehiculosDeEstacion(int $estacion)
    {
        return DB::table('automovil')
            ->where('COD_EST', $estacion)
            ->orderBy('PLA_VEH')
            ->get(['COD_VEH', 'PLA_VEH', 'MOD_VEH']);
    }

    private function combustibles()
    {
        return DB::table('tipo_combustible')
            ->orderBy('DES_COM')
            ->get(['COD_COM', 'DES_COM']);
    }

    private function rules(Usuario $usuario): array
    {
        return [
            'COD_VEH' => [
                'required',
                'integer',
                Rule::exists('automovil', 'COD_VEH')
                    ->where('COD_EST', $usuario->COD_EST),
            ],
            'COD_COM' => ['required', 'integer', 'exists:tipo_combustible,COD_COM'],
            'CAN_VEN' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:99999999.99'],
            'PRE_VEN' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:99999999.99'],
        ];
    }

    private function validatedData(Request $request, Usuario $usuario): array
    {
        $data = $request->validate($this->rules($usuario));

        if ((float) $data['CAN_VEN'] * (float) $data['PRE_VEN'] > 99999999.99) {
            throw ValidationException::withMessages([
                'CAN_VEN' => 'El total de la venta supera el máximo permitido.',
            ]);
        }

        return $data;
    }
}
