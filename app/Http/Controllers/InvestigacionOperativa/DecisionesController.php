<?php

namespace App\Http\Controllers\InvestigacionOperativa;

use App\Http\Controllers\Controller;
use App\Support\InvestigacionOperativa\TeoriaDecisiones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

/** Teoría de Decisiones (informe, sección 6): ¿a qué estación se envía primero la cisterna? */
class DecisionesController extends Controller
{
    private const ESTADOS = ['Demanda alta', 'Demanda media', 'Demanda baja'];

    /** Tabla 1 del informe (vehículos atendidos; datos ficticios). */
    private const PAGOS_INFORME = [[500, 300, 150], [200, 150, 100], [350, 250, 120]];

    /** Ejemplo didáctico en el que valor esperado y Wald recomiendan estaciones distintas. */
    private const PAGOS_CONTRASTE = [[500, 200, 20], [250, 220, 200], [350, 250, 120]];

    /** Probabilidades históricas por defecto (en %), para demanda alta / media / baja. */
    private const PROBABILIDADES = [30, 50, 20];

    public function index(Request $request)
    {
        $candidatas = $this->estacionesEnAlertaDeReorden();
        $cisternas = DB::table('cisterna')->where('ESD_CIS', 'disponible')->count();

        $nombres = $candidatas->take(3)->pluck('NOM_EST')->values()->all();
        for ($i = 0; $i < 3; $i++) {
            $nombres[$i] ??= 'Estación '.chr(65 + $i);
        }
        $pagos = $request->query('ejemplo') === 'contraste' ? self::PAGOS_CONTRASTE : self::PAGOS_INFORME;
        $probs = self::PROBABILIDADES;
        $mensajes = [];

        if ($request->has('pagos')) {
            $v = Validator::make($request->query(), [
                'nombres' => ['required', 'array', 'size:3'],
                'nombres.*' => ['required', 'string', 'max:60'],
                'pagos' => ['required', 'array', 'size:3'],
                'pagos.*' => ['required', 'array', 'size:3'],
                'pagos.*.*' => ['required', 'numeric', 'min:0'],
                'probs' => ['required', 'array', 'size:3'],
                'probs.*' => ['required', 'numeric', 'min:0', 'max:100'],
            ]);
            if ($v->fails()) {
                $mensajes = $v->errors()->all();
            } else {
                $d = $v->validated();
                $nombres = array_values($d['nombres']);
                $pagos = array_map('array_values', array_values($d['pagos']));
                $probs = array_map('floatval', array_values($d['probs']));
            }
        }

        $resultado = null;
        try {
            $resultado = (new TeoriaDecisiones($nombres, self::ESTADOS, $pagos, array_map(fn ($p) => $p / 100, $probs)))->resolver();
        } catch (InvalidArgumentException $e) {
            $mensajes[] = $e->getMessage();
        }

        return view('investigacion.decisiones', [
            'estados' => self::ESTADOS,
            'nombres' => $nombres,
            'pagos' => $pagos,
            'probs' => $probs,
            'resultado' => $resultado,
            'mensajes' => $mensajes,
            'candidatas' => $candidatas,
            'cisternas' => $cisternas,
        ]);
    }

    /** Estaciones activas con al menos un combustible en o bajo su mínimo (alerta de reorden). */
    private function estacionesEnAlertaDeReorden()
    {
        $g = DB::getQueryGrammar();

        return DB::table('existencia_estacion as x')
            ->join('estacion as e', 'e.COD_EST', '=', 'x.COD_EST')
            ->where('e.ACT_EST', true)
            ->whereColumn('x.CAN_EXI', '<=', 'e.MIN_EST')
            ->groupBy('e.COD_EST', 'e.NOM_EST', 'e.ZON_EST')
            ->select('e.COD_EST', 'e.NOM_EST', 'e.ZON_EST',
                DB::raw('COUNT(*) as combustibles_bajos'),
                DB::raw('MIN('.$g->wrap('x.CAN_EXI').') as minimo'))
            ->orderBy('minimo')
            ->get();
    }
}