<?php

namespace App\Http\Controllers\Abastecimiento;

use App\Http\Controllers\Controller;
use App\Models\PlantaDeposito;
use App\Models\TanqueAlmacenamiento;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TanqueController extends Controller
{
    private const ESTADOS = ['operativo', 'critico', 'mantenimiento', 'inactivo'];

    private function reglas(): array
    {
        return [
            'COD_PLA' => ['required', 'integer', 'exists:planta_deposito,COD_PLA'],
            'COD_COM' => ['required', 'integer', 'exists:tipo_combustible,COD_COM'],
            'NOM_TAN' => ['required', 'string', 'max:60'],
            'CAP_TAN' => ['required', 'numeric', 'gt:0', 'max:99999999.99'],
            'NIV_TAN' => ['required', 'numeric', 'min:0', 'lte:CAP_TAN'],
            'ESD_TAN' => ['required', 'in:' . implode(',', self::ESTADOS)],
        ];
    }

    private function datosForm(): array
    {
        return [
            'plantas'      => PlantaDeposito::orderBy('NOM_PLA')->get(),
            'combustibles' => DB::table('tipo_combustible')->orderBy('DES_COM')->get(),
            'estados'      => self::ESTADOS,
        ];
    }

    public function index()
    {
        $items = TanqueAlmacenamiento::query()
            ->join('planta_deposito', 'planta_deposito.COD_PLA', '=', 'tanque_almacenamiento.COD_PLA')
            ->join('tipo_combustible', 'tipo_combustible.COD_COM', '=', 'tanque_almacenamiento.COD_COM')
            ->select('tanque_almacenamiento.*', 'planta_deposito.NOM_PLA', 'tipo_combustible.DES_COM')
            ->orderBy('tanque_almacenamiento.COD_TAN')
            ->get();
        return view('abastecimiento.tanques.index', compact('items'));
    }

    public function create()
    {
        return view('abastecimiento.tanques.form', $this->datosForm());
    }

    public function store(Request $request)
    {
        TanqueAlmacenamiento::create($request->validate($this->reglas()));
        return redirect()->route('abast.tanques.index')->with('ok', 'Tanque creado.');
    }

    public function edit(TanqueAlmacenamiento $tanque)
    {
        return view('abastecimiento.tanques.form', $this->datosForm() + ['item' => $tanque]);
    }

    public function update(Request $request, TanqueAlmacenamiento $tanque)
    {
        $tanque->update($request->validate($this->reglas()));
        return redirect()->route('abast.tanques.index')->with('ok', 'Tanque actualizado.');
    }

    public function destroy(TanqueAlmacenamiento $tanque)
    {
        try {
            $tanque->delete();
        } catch (QueryException $e) {
            return back()->withErrors('No se puede eliminar: el tanque está referenciado por otros registros. Ponelo en estado inactivo.');
        }
        return redirect()->route('abast.tanques.index')->with('ok', 'Tanque eliminado.');
    }
}