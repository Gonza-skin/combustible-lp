<?php

namespace App\Support\InvestigacionOperativa;

use InvalidArgumentException;

/**
 * Decisión bajo riesgo/incertidumbre (informe, sección 6).
 * Alternativas = estaciones a las que se puede enviar la cisterna.
 * Estados de la naturaleza = demanda alta / media / baja.
 * Pagos = vehículos atendidos (se quiere maximizar).
 *   - Valor esperado: Σ p_j · pago_ij  (usa las probabilidades históricas).
 *   - Wald (maximin): el máximo de los peores resultados (criterio conservador, sin probabilidades).
 */
final class TeoriaDecisiones
{
    /**
     * @param string[]           $alternativas
     * @param string[]           $estados
     * @param array<int,float[]> $pagos           pagos[i][j]
     * @param float[]            $probabilidades  una por estado; deben sumar 1
     */
    public function __construct(
        private array $alternativas,
        private array $estados,
        private array $pagos,
        private array $probabilidades,
    ) {
        if (count($pagos) !== count($alternativas)) {
            throw new InvalidArgumentException('Hay una fila de pagos por cada alternativa.');
        }
        foreach ($pagos as $fila) {
            if (count($fila) !== count($estados)) {
                throw new InvalidArgumentException('Cada fila de pagos debe tener un valor por estado.');
            }
        }
        if (count($probabilidades) !== count($estados) || min($probabilidades) < 0
            || abs(array_sum($probabilidades) - 1) > 0.001) {
            throw new InvalidArgumentException('Las probabilidades deben ser positivas y sumar 100 %.');
        }
    }

    public function resolver(): array
    {
        $ve = $min = [];
        foreach ($this->pagos as $i => $fila) {
            $ve[$i] = 0.0;
            foreach ($fila as $j => $pago) {
                $ve[$i] += $pago * $this->probabilidades[$j];
            }
            $min[$i] = min($fila);
        }
        $mejorVe = self::indiceMax($ve);
        $mejorWald = self::indiceMax($min);

        return [
            'valores_esperados' => $ve,
            'minimos' => $min,
            'mejor_valor_esperado' => $mejorVe,
            'mejor_wald' => $mejorWald,
            'coinciden' => $mejorVe === $mejorWald,
        ];
    }

    private static function indiceMax(array $v): int
    {
        $mejor = 0;
        foreach ($v as $i => $x) {
            if ($x > $v[$mejor] + 1e-9) {
                $mejor = $i;
            }
        }

        return $mejor;
    }
}