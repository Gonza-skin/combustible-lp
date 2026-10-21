<?php

namespace App\Http\Controllers\InvestigacionOperativa;

use App\Http\Controllers\Controller;
use App\Support\InvestigacionOperativa\CadenaMarkov;
use App\Support\InvestigacionOperativa\HistorialEstados;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/** Cadenas de Markov (informe, sección 7): predicción de disponibilidad por estación. */
class MarkovController extends Controller
{
    /** Con menos transiciones propias que esto, la estación usa la matriz global. */
    private const MIN_TRANSICIONES = 10;

    private const ESTADO_DE_ESTACION = ['normal' => 0, 'nivel_bajo' => 1, 'sin_combustible' => 2];

    public function index(Request $request)
    {
        $d = $request->validate([
            'dias' => ['nullable', 'integer', 'min:1', 'max:60'],
            'umbral' => ['nullable', 'numeric', 'min:1', 'max:100'],
            'estacion' => ['nullable', 'integer'],
            'fuente' => ['nullable', 'in:real,informe'],
        ]);
        $dias = (int) ($d['dias'] ?? 3);
        $umbral = (float) ($d['umbral'] ?? 25);
        $fuente = $d['fuente'] ?? 'real';

        $estaciones = DB::table('estacion')->where('ACT_EST', true)->orderBy('NOM_EST')
            ->get(['COD_EST', 'NOM_EST', 'ZON_EST', 'ESD_EST']);

        $observaciones = DB::table('reporte_ciudadano')
            ->where('FEC_REP', '>=', now()->subDays(180)->startOfDay())
            ->orderBy('FEC_REP')
            ->get(['COD_EST', 'FIL_REP', 'DIS_REP', 'FEC_REP'])
            ->map(fn ($r) => [$r->COD_EST, Carbon::parse($r->FEC_REP)->toDateString(),
                HistorialEstados::estadoDeReporte($r->DIS_REP, $r->FIL_REP)]);

        $c = HistorialEstados::conteos($observaciones);
        $matrizGlobal = CadenaMarkov::desdeConteos($c['global']);

        $filas = $estaciones->map(fn ($e) => $this->analizar($e, $c, $matrizGlobal, $fuente, $dias, $umbral))
            ->sortByDesc('riesgo')->values();

        $detalle = $filas->firstWhere('estacion.COD_EST', (int) ($d['estacion'] ?? 0)) ?? $filas->first();

        return view('investigacion.markov', [
            'filas' => $filas,
            'detalle' => $detalle,
            'dias' => $dias,
            'umbral' => $umbral,
            'fuente' => $fuente,
            'estaciones' => $estaciones,
            'enAlerta' => $filas->where('alerta', true)->count(),
            'transicionesTotales' => HistorialEstados::totalTransiciones($c['global']),
            'estados' => CadenaMarkov::ESTADOS,
        ]);
    }

    private function analizar(object $e, array $c, ?array $matrizGlobal, string $fuente, int $dias, float $umbral): array
    {
        $propios = $c['porEstacion'][$e->COD_EST] ?? null;
        $p = null;
        $origen = 'ejemplo';
        if ($fuente === 'real') {
            if ($propios && HistorialEstados::totalTransiciones($propios) >= self::MIN_TRANSICIONES
                && ($pe = CadenaMarkov::desdeConteos($propios))) {
                [$p, $origen] = [$pe, 'estacion'];
            } elseif ($matrizGlobal) {
                [$p, $origen] = [$matrizGlobal, 'global'];
            }
        }
        $p ??= HistorialEstados::MATRIZ_INFORME;

        // Estado de hoy: el modo "informe" reproduce el ejemplo (hoy = Disponible); si no, el último reporte.
        $ultimo = $c['ultimo'][$e->COD_EST] ?? null;
        $actual = $fuente === 'informe' ? 0 : ($ultimo['estado'] ?? self::ESTADO_DE_ESTACION[$e->ESD_EST] ?? 0);
        $a0 = [0.0, 0.0, 0.0];
        $a0[$actual] = 1.0;

        $cadena = new CadenaMarkov($p);
        $an = $cadena->distribucion($a0, $dias);
        try {
            $pi = $cadena->estacionaria();
        } catch (RuntimeException) {
            $pi = null;
        }
        try {
            $mu = $cadena->diasHastaAgotamiento();
        } catch (RuntimeException) {
            $mu = null;
        }

        return [
            'estacion' => $e,
            'origen' => $origen,
            'matriz' => $p,
            'conteos' => $propios,
            'transiciones' => $propios ? HistorialEstados::totalTransiciones($propios) : 0,
            'ultimo' => $ultimo,
            'actual' => $actual,
            'a0' => $a0,
            'an' => $an,
            'pi' => $pi,
            'mu' => $mu,
            'riesgo' => $an[2],
            'dias_hasta' => $actual === 2 ? 0.0 : ($mu[$actual] ?? null),
            'agotada_mes' => $pi ? $pi[2] * 30 : null,
            'alerta' => $an[2] * 100 >= $umbral,
        ];
    }
}