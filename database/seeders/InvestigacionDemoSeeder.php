<?php

namespace Database\Seeders;

use App\Models\Estacion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DATOS FICTICIOS para demostrar Investigación Operativa:
 * 5 estaciones de La Paz con ~75 días de reportes ciudadanos simulados con una cadena de Markov
 * (cada estación con su propia matriz) y existencias, algunas bajo el mínimo para activar las alertas de reorden.
 *
 *   php artisan db:seed --class=InvestigacionDemoSeeder
 *
 * Es re-ejecutable: borra y vuelve a generar solo los reportes anónimos (sin usuario) de estas estaciones.
 */
class InvestigacionDemoSeeder extends Seeder
{
    private const DIAS = 75;

    public function run(): void
    {
        mt_srand(2026); // resultados repetibles

        // nombre, zona, lat, lon, existencia (L) por combustible, matriz de transición propia
        $demo = [
            ['Surtidor Zavaleta', 'Zavaleta', -16.4850, -68.1280, 800, [[.55, .25, .20], [.25, .40, .35], [.15, .35, .50]]],
            ['Surtidor Miraflores', 'Miraflores', -16.5040, -68.1200, 1500, [[.70, .20, .10], [.30, .50, .20], [.20, .40, .40]]],
            ['Surtidor Calacoto', 'Calacoto', -16.5380, -68.0810, 9000, [[.80, .15, .05], [.35, .50, .15], [.25, .45, .30]]],
            ['Surtidor Sopocachi', 'Sopocachi', -16.5120, -68.1280, 15000, [[.75, .18, .07], [.30, .50, .20], [.20, .40, .40]]],
            ['Surtidor Obrajes', 'Obrajes', -16.5250, -68.1000, 1900, [[.65, .22, .13], [.28, .47, .25], [.18, .38, .44]]],
        ];
        $estadoEstacion = ['normal', 'nivel_bajo', 'sin_combustible'];
        $combustibles = DB::table('tipo_combustible')->pluck('COD_COM');

        foreach ($demo as [$nombre, $zona, $lat, $lon, $existencia, $matriz]) {
            $estacion = Estacion::updateOrCreate(['NOM_EST' => $nombre], [
                'DIR_EST' => $zona.', La Paz',
                'ZON_EST' => $zona,
                'LAT_EST' => $lat,
                'LON_EST' => $lon,
                'MIN_EST' => 2000,
                'MAX_EST' => 20000,
                'TPT_EST' => 60,
                'ESD_EST' => 'normal',
                'ACT_EST' => true,
            ]);

            foreach ($combustibles as $cod) {
                DB::table('existencia_estacion')->updateOrInsert(
                    ['COD_EST' => $estacion->COD_EST, 'COD_COM' => $cod],
                    ['CAN_EXI' => $existencia]
                );
            }

            DB::table('reporte_ciudadano')->where('COD_EST', $estacion->COD_EST)->whereNull('COD_USU')->delete();

            $estado = 0;
            $filas = [];
            for ($d = self::DIAS; $d >= 1; $d--) {
                $estado = $this->siguiente($matriz[$estado]);
                $dia = now()->subDays($d)->startOfDay();
                for ($r = 0, $n = mt_rand(1, 3); $r < $n; $r++) {
                    $filas[] = $this->reporte($estacion->COD_EST, $estado, $dia->copy()->addMinutes(mt_rand(360, 1200)));
                }
            }
            DB::table('reporte_ciudadano')->insert($filas);
            $estacion->update(['ESD_EST' => $estadoEstacion[$estado]]);
        }
    }

    private function siguiente(array $fila): int
    {
        $u = mt_rand() / mt_getrandmax();
        $acum = 0;
        foreach ($fila as $j => $p) {
            $acum += $p;
            if ($u <= $acum) {
                return $j;
            }
        }

        return count($fila) - 1;
    }

    /** 0 Disponible, 1 Fila larga, 2 Agotado → campos del reporte ciudadano. */
    private function reporte(int $codEst, int $estado, $fecha): array
    {
        $fil = [['sin_fila', 'corta'], ['moderada', 'larga'], ['sin_fila', 'corta', 'moderada', 'larga']][$estado];
        $dis = [['disponible'], ['disponible', 'poco_stock'], ['agotado']][$estado];

        return [
            'COD_EST' => $codEst,
            'COD_USU' => null,
            'FIL_REP' => $fil[array_rand($fil)],
            'DIS_REP' => $dis[array_rand($dis)],
            'TES_REP' => [mt_rand(0, 15), mt_rand(25, 90), 0][$estado],
            'FEC_REP' => $fecha->format('Y-m-d H:i:s'),
        ];
    }
}