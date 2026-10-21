<?php

namespace App\Support\InvestigacionOperativa;

/**
 * Convierte el historial de reportes ciudadanos en conteos de transiciones n_ij.
 * Un "día" de una estación toma el PEOR estado reportado ese día (el informe observa un estado por día).
 * Solo se cuenta una transición entre días CONSECUTIVOS con observación.
 */
final class HistorialEstados
{
    /** Matriz ilustrativa del informe (Tabla 2, datos ficticios). */
    public const MATRIZ_INFORME = [
        [0.70, 0.20, 0.10],
        [0.30, 0.50, 0.20],
        [0.20, 0.40, 0.40],
    ];

    /** Estado de un reporte: 2 Agotado > 1 Fila larga > 0 Disponible. */
    public static function estadoDeReporte(?string $disponibilidad, ?string $fila): int
    {
        if ($disponibilidad === 'agotado') {
            return 2;
        }
        if (in_array($fila, ['moderada', 'larga'], true)) {
            return 1;
        }

        return 0;
    }

    /**
     * @param iterable<array{0:int|string,1:string,2:int}> $observaciones [estacion, 'Y-m-d', estado]
     * @return array{porEstacion: array, global: array, ultimo: array}
     */
    public static function conteos(iterable $observaciones): array
    {
        $dias = []; // estacion => fecha => peor estado
        foreach ($observaciones as [$est, $fecha, $estado]) {
            $dias[$est][$fecha] = max($dias[$est][$fecha] ?? 0, $estado);
        }

        $cero = fn () => array_fill(0, 3, array_fill(0, 3, 0));
        $global = $cero();
        $porEstacion = $ultimo = [];

        foreach ($dias as $est => $serie) {
            ksort($serie);
            $n = $porEstacion[$est] = $cero();
            $anteriorFecha = $anteriorEstado = null;
            foreach ($serie as $fecha => $estado) {
                if ($anteriorFecha !== null
                    && (strtotime($fecha) - strtotime($anteriorFecha)) === 86400) {
                    $n[$anteriorEstado][$estado]++;
                    $global[$anteriorEstado][$estado]++;
                }
                $anteriorFecha = $fecha;
                $anteriorEstado = $estado;
            }
            $porEstacion[$est] = $n;
            $ultimo[$est] = ['fecha' => $anteriorFecha, 'estado' => $anteriorEstado];
        }

        return ['porEstacion' => $porEstacion, 'global' => $global, 'ultimo' => $ultimo];
    }

    public static function totalTransiciones(array $n): int
    {
        return (int) array_sum(array_map('array_sum', $n));
    }
}